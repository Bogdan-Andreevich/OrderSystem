<template>
    <div class="workflow">
        <section class="top-selector">
            <select class="form-control top-select top-select-1" v-model="selectedOption">
                <option v-for="option in options" :value="option.id">{{ option.name }} -- {{ option.categoryName }}
                </option>
            </select>

            <div class="input-group top-selector-section">
                <select class="form-control p-selector top-select top-select-2" v-model="selectedOptionCopy">
                    <option v-for="option in options" :value="option.id">{{ option.name }} -- {{ option.categoryName }}
                    </option>
                </select>

                <span class="input-group-append">
                    <button class="btn btn-info btn-flat" @click="copyCategories">Копіювати прайс з іншого ТЗ</button>
                </span>
            </div>
        </section>


        <div class="middle-selector form-group"> <button class="btn btn-default" @click="addOptionsToActions">+</button>


            <MultiSelect v-model="selectedOptions" :options="prices" optionLabel="name" optionValue="id" filter
                placeholder="Виберіть опції" class="w-full md:w-14rem select2" style="width:100%;">

                <template #option="slotProps">
                    <div class="flex align-items-center">
                        <div>{{ slotProps.option.name }} - {{ slotProps.option.price }} грн</div>
                    </div>
                </template>

            </MultiSelect>

            <div class="checkbox"> <input type="checkbox" id="allCategories" v-model="allCategories">
                <label for="allCategories">Всі категорії</label>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table table-striped table-bordered">
                <draggable tag="tbody" v-model="actions" group="actions" @start="dragStart" @end="dragEnd"
                    item-key="index">
                    <template #item="{ element, index }">
                        <tr class="custom-list">
                            <td>{{ element.name }}</td>
                            <td>{{ element.price }}</td>
                            <td>{{ element.unit }}</td>
                            <td>
                                <button class="btn btn-danger" @click="deleteAction(index)">Видалити</button>
                            </td>
                            <td>{{ element.categoryName }}</td>
                        </tr>
                    </template>
                </draggable>
            </table>

            <button class="btn btn-success" @click="saveData">Зберегти</button>
        </div>

    </div>
</template>

<script>
import draggable from 'vuedraggable'

export default {
    components: {
        draggable,
    },

    name: 'PricesComponent',
    mounted() {
        this.fetchDataCategories().then(() => {
            this.fetchDataTypeOfOrders().then(() => {
                const optionIdData = sessionStorage.getItem("optionId")

                if (optionIdData) {
                    this.changeSelection(+optionIdData)
                    sessionStorage.removeItem("optionId")
                }
            })
        })

    },
    watch: {
        selectedOption(newOptionId) {
            this.optionId = newOptionId;
            console.log(this.options);
            const categoryId = this.options.find((item) => item.id === newOptionId).categoryId
            this.fetchPriceData(categoryId);
            this.fetchActions(newOptionId);
        },
        allCategories(isChecked) {
            if (isChecked) {
                this.fetchAllPrices();
            } else {
                this.fetchPriceData(this.optionId)
            }
        }

    },

    data() {
        return {
            drag: false,
            selectedOption: null,
            selectedOptionCopy: null,
            allCategories: false,
            optionId: null,
            options: [],
            optionsCopy: [],
            prices: [],
            actions: [],
            selectedOptions: []
        };
    },
    methods: {
        changeSelection(newOptionId) {
            this.selectedOption = newOptionId;
        },
        async copyCategories() {
            try {
                const response = await this.axios.get(`http://crm-test.san-sanych.in.ua/api/price/rows/${this.selectedOptionCopy}`);
                const copiedCategories = response.data.categories; // Assuming your API returns the categories.

                // Add a check to verify API response:
                if (!Array.isArray(copiedCategories)) {
                    throw new Error('Invalid data returned from the API.');
                }

                this.actions.push(...copiedCategories);

            } catch (error) {
                console.error('Error copying categories:', error);
            }
        },
        async saveData() {
            try {

                const isExistCategory = await this.fetchActionsCheck(this.optionId); // Check synchronously
                const httpMethod = isExistCategory ? 'put' : 'post';
                const url = isExistCategory ? `http://crm-test.san-sanych.in.ua/api/price/rows/${this.optionId}` : 'http://crm-test.san-sanych.in.ua/api/price/rows';

                const categories = this.actions.map((item) => item.id);
                await this.axios[httpMethod](url, {
                    priceId: this.optionId,
                    categories: categories.length == 0 ? [-1] : categories
                });

                alert(isExistCategory ? 'Data updated' : 'Data saved');  // Conditional success message

            } catch (error) {
                console.error('Error saving data:', error);
                // Handle error gracefully, e.g., display an error message to the user
            }
        },
        addOptionsToActions() {
            const selectedData = this.selectedOptions.map(id => {
                // Find the full price object based on the selected ID
                const matchingPrice = this.prices.find(price => price.id === id);
                return matchingPrice; // Or return a modified version 
            });


            if (this.actions.length === 0) {
                this.actions.push(...selectedData);
            } else {
                selectedData.forEach((item) => {
                    const isPriceAdded = this.actions.find((itemAction) => itemAction.id === item.id)
                    if (!isPriceAdded) this.actions.push(item);
                })
            }



        },
        deleteAction(index) {
            this.actions.splice(index, 1); // Remove the element at the given index
        },
        async fetchAllPrices() {
            try {
                const response = await this.axios.get('http://crm-test.san-sanych.in.ua/api/prices');
                this.prices = response.data;
            } catch (error) {
                console.error('Error fetching prices:', error);
            }
        },
        async fetchDataTypeOfOrders() {
            try {
                const response = await this.axios.get('http://crm-test.san-sanych.in.ua/api/typeoforders');
                this.options = response.data.map((item) => ({ ...item, categoryName: this.categories.find((itemCat) => itemCat.id === item.categoryId)?.name, techDocumentations: typeof techDocumentations === "JSON" ? JSON.parse(item.techDocumentations) : [] }));
                this.optionsCopy = this.options;

            } catch (error) {
                console.error('Error fetching typeoforders:', error);
            }
        },
        async fetchDataCategories() {
            try {
                const response = await this.axios.get('http://crm-test.san-sanych.in.ua/api/categories');
                this.categories = response.data;

            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        },
        async fetchPriceData(priceId) {
            try {
                const response = await this.axios.get(`http://crm-test.san-sanych.in.ua/api/prices/${priceId}`);
                this.prices = response.data[0];
            } catch (error) {
            }
        },
        async fetchActions(priceId) {
            try {
                const response = await this.axios.get(`http://crm-test.san-sanych.in.ua/api/price/rows/${priceId}`);
                console.log(response.data, 'HERE');
                this.actions = response.data.categories.sort((a, b) => {
                    return response.data.categoriesOrders.indexOf(a.id) - response.data.categoriesOrders.indexOf(b.id);
                }).map((item) => ({ ...item, categoryName: this.categories.find((category) => category.id === item.categoryId)?.name }))

            } catch (error) {
                this.actions = [];
            }
        },
        async fetchActionsCheck(priceId) {
            const response = await this.axios.get(`http://crm-test.san-sanych.in.ua/api/price/rows/${priceId}`);
            return response.data.categories;
        }
    }
};
</script>

<style scoped>
.workflow {
    /* Your CSS styles here */
}

.actions {
    /* Styles for the action section */
}

.timeline {
    /* Styles for the timeline section */
}

.dot {
    /* Styles for the dot in the timeline */
}

.action-item {
    /* Styles for each action item */
}

.top-select-1 {
    margin-bottom: 20px;
}

.select2 {
    max-width: 500px;
}

.middle-selector {
    margin-top: 50px;
    display: flex;
    align-items: flex-end;
}

.checkbox {
    display: flex;
    align-items: baseline;
    margin-left: 20px;
    column-gap: 10px;
}

.custom-list:before {
    content: "••";
    font-size: 24px;
    vertical-align: middle;
    padding-left: 10px;
    margin-right: -10px;
}

/* ... other styles */
</style>