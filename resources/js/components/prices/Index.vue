<template>
    <div class="workflow">
        <section class="top-selector">
            <select class="form-control top-select top-select-1" v-model="selectedOption">
                <option v-for="option in options" :value="option.value">{{ option.text }}</option>
            </select>

            <div class="input-group top-selector-section">
                <select class="form-control p-selector top-select top-select-2" v-model="selectedOption">
                    <option v-for="option in options" :value="option.value">{{ option.text }}</option>
                </select>

                <span class="input-group-append">
                    <button class="btn btn-info btn-flat">Копіювати прайс з іншого ТЗ</button>
                </span>
            </div>
        </section>


        <div class="middle-selector form-group"> <button class="btn btn-default">+</button>


            <Dropdown v-model="selectedOption" :options="options" filter optionLabel="text" placeholder="Виберіть опцію"
                class="w-full md:w-14rem select2" style="width:100%;">
                <template #value="slotProps">
                    <div v-if="slotProps.value" class="flex align-items-center">
                        <div>{{ slotProps.value.text }}</div>
                    </div>
                    <span v-else>
                        {{ slotProps.placeholder }}
                    </span>
                </template>

                <template #option="slotProps">
                    <div class="flex align-items-center">
                        <div>{{ slotProps.option.text }}</div>
                    </div>
                </template>
            </Dropdown>



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
                            <td>{{ element.amount }}</td>
                            <td>
                                <button class="btn btn-danger" @click="deleteAction(index)">Видалити</button>
                            </td>
                        </tr>
                    </template>
                </draggable>
            </table>

            <button class="btn btn-success">Зберегти</button>
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
    },
    data() {
        return {
            drag: false,
            selectedOption: null,
            allCategories: false,
            options: [
                { value: 'installation', text: 'встановлення унітазу' },
                { value: 'removal', text: 'складний демонтаж унітазу - 300 грн' },
            ],
            actions: [
                { name: 'встановлення унітазу', amount: '500 шт.', error: false },
                { name: 'простий демонтаж унітазу', amount: '200 шт.', error: false },
                { name: 'заміна унітазу', amount: '600 шт.', error: true },
            ]
        };
    },
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