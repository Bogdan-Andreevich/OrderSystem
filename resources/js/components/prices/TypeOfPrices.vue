<template>
    <section class="content"> 
      <div class="container-fluid">
        <div class="row">
          <div class="col-12"> 
            <CategoryComponent @valueChanged="handleValueChange" />
          </div>
        </div>
        <div class="row">  
          <div class="col-12"> 
            <div class="card">
              <div class="card-header">
                <div class="card-tools">
                  <button class="btn btn-primary btn-sm" @click="addPriceItem">
                    <i class="fas fa-plus"></i>  &nbsp;Add
                  </button>
                </div>
              </div>
              <div class="card-body">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Найменування</th>
                      <th>рос</th>
                      <th>ціна</th>
                      <th>од. вим.</th>
                      <th>Категорія</th>
                      <th>ТЗ</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="order in orders" :key="order.id">
                      <td>
                        <input type="text" v-model="order.name" class="form-control form-control-sm">
                      </td>
                      <td>
                        <input type="text" v-model="order.nameRu" class="form-control form-control-sm">
                      </td>
                      <td>
                        <input type="text" v-model="order.price" class="form-control form-control-sm">
                      </td>
                      <td>
                        <select v-model="order.unit" class="form-control form-control-sm">
                          <option value="шт">шт</option>
                          <option value="м2">м2</option>
                          <option value="м/пог">м/пог</option>
                        </select>
                      </td>
                      <td>
                        <select v-model="order.categoryId" class="form-control form-control-sm">
                          <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                          </option>
                        </select>
                      </td>
                      <td>
                        {{ order.techDocumentations.length }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="card-footer">
                <button class="btn btn-success" @click="saveChanges">Зберегти</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
<script>
import CategoryComponent from '../categories/Index.vue'

export default {
    components: {
        CategoryComponent
    },
    name: 'PriceOfOrders',
    mounted() {
        this.fetchCategories();
        this.fetchData();
    },
    methods: {
        async saveChanges() {
            try {
                for (const item of this.orders) {
                    if (item.id) {
                        // Existing item -> Update
                        await this.axios.put(`http://localhost/api/prices/${item.id}`, item);
                    } else {
                        // New item -> Create
                        await this.axios.post('http://localhost/api/prices', item);
                    }
                }
                alert('Changes saved!'); // Or a more suitable success message
            } catch (error) {
                console.error('Error saving changes:', error);
                // Handle the error appropriately
            }
        },
        handleValueChange(newValue) {
            this.categoryId = newValue;
            this.orders = newValue===0 ? this.allOrders : this.allOrders.filter((item) => +item.categoryId === +newValue)

        },
        async fetchData() {
            try {
                const response = await this.axios.get('http://localhost/api/prices');
                this.orders = response.data.map((item) => ({ ...item, techDocumentations: JSON.parse(item.techDocumentations) }));
                this.allOrders = this.orders;
            } catch (error) {
                console.error('Error fetching prices:', error);
            }
        },
        async fetchCategories() {
            try {
                const response = await this.axios.get('http://localhost/api/categories');
                this.categories = response.data.slice(1);
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        },
        addPriceItem() {
            // Create a copy or new object to avoid reactivity issues
            const newItem = { ...this.newPriceItem };

            this.orders.push(newItem);

            // Reset newPriceItem for the next entry 
            this.newPriceItem = {
                name: "",
                nameRu: "",
                price: "",
                unit: "",
                price: null,
                techDocumentations: [],
                categoryId: this.categoryId,
            };
        },

    },
    data() {
        return {
            allOrders: [],
            categoryId: 1,
            orders: [],
            categories: [],
            newPriceItem: {
                name: "",
                nameRu: "",
                price: "",
                unit: "",
                price: null,
                techDocumentations: [],
                categoryId: this.categoryId,
            }
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
</style>