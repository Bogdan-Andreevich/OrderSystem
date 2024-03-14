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
              <div class="card-tools" style="float: left;">
                <button class="btn btn-primary btn-sm" @click="addPriceItem">
                  <i class="fas fa-plus"></i> додати ТЗ
                </button>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Тип замовлення</th>
                    <th>рос</th>
                    <th></th>
                    <th>Категорія</th>
                    <th>Відповідний в пошуку</th>
                    <th></th>
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
                      <a href="/account/prices" @click="handleNavigate(order.categoryId, order.id, $event)"
                        class="btn btn-sm btn-primary">ТЗ</a>
                    </td>
                    <td>
                      <select v-model="order.categoryId" class="form-control form-control-sm">
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                          {{ category.name }}
                        </option>
                      </select>
                    </td>
                    <td>
                      <select v-model="order.searchId" class="form-control form-control-sm">
                        <option v-for="search in searches" :key="search.id" :value="search.id">
                          {{ search.name }}
                        </option>
                      </select>
                    </td>
                    <td>
  <button class="btn btn-danger btn-sm" @click="handleDelete(order.id)">Delete</button>
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
    // TODO: check it this data
    // this.fetchSearch();
  },
  methods: {
    handleNavigate(categoryId, id, event) { 
  sessionStorage.setItem("optionId", id);
  sessionStorage.setItem("categoryId", categoryId);

  // Assuming your href is the intended destination:
  const linkDestination = event.target.href; 

  // Simulate a click with a slight delay
  setTimeout(() => {
    window.location.href = linkDestination; 
  }, 100); // Adjust delay if needed
},
    async saveChanges() {
      if(this.isSave) return;
            this.isSave = true;
      try {
        for (const item of this.orders) {
          if (item.id) {
            // Existing item -> Update
            await this.axios.put(`http://localhost/api/typeoforders/${item.id}`, {...item, techDocumentations: JSON.stringify(item.techDocumentations)});
            
          } else {
            // New item -> Create
            await this.axios.post('http://localhost/api/typeoforders', item);
          }
        }
        this.fetchData();
        this.isSave = false;
        alert('Changes saved!'); // Or a more suitable success message
      } catch (error) {
        console.error('Error saving changes:', error);
        // Handle the error appropriately
      }
    },
    handleValueChange(newValue) {
      this.parentCategoryId = newValue;
      this.orders = newValue === 0 ? this.allOrders : this.allOrders.filter((item) => +item.categoryId === +newValue)

    },
    async fetchData() {
      try {
        const response = await this.axios.get('http://localhost/api/typeoforders');
        this.allOrders = response.data.map((item) => ({ ...item, techDocumentations: typeof techDocumentations === "JSON" ? JSON.parse(item.techDocumentations) : [] }));;
        if(this.parentCategoryId){
          this.orders = newValue === 0 ? this.allOrders : this.allOrders.filter((item) => +item.categoryId === +this.parentCategoryId)
        }else{
          this.orders = response.data.map((item) => ({ ...item, techDocumentations: typeof techDocumentations === "JSON" ? JSON.parse(item.techDocumentations) : [] }));
        }
      } catch (error) {
        console.error('Error fetching typeoforders:', error);
      }
    },
    async fetchCategories() {
      try {
        const response = await this.axios.get('http://localhost/api/categories');
        this.categories = response.data;
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    },
    async fetchSearch() {
      try {
        const response = await this.axios.get('/bot/api/dict.php?table=ordertype');
        this.searches = response.data;
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
        searchId: 0,
        categoryId: this.categoryId,
      };
    },
    handleDelete(id) {
      if (confirm("Are you sure you want to delete this order?")) {
        this.axios.delete('http://localhost/api/typeoforders/' + id)
          .then(response => {
            this.orders = this.orders.filter(order => order.id !== id); // Example
          })
          .catch(error => {
            console.error('Error deleting order:', error);
            // Handle error gracefully
          });
      }
    }


  },
  data() {
    return {
      isSave: false,
      searches: [{
        id: 1,
        name: "test"
      }],
      allOrders: [],
      parentCategoryId: null,
      orders: [],
      categories: [],
      newPriceItem: {
        name: "",
        nameRu: "",
        searchId: 0,
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