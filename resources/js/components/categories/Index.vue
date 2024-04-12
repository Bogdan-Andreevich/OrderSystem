<template>
  <nav class="navbar navbar-expand navbar-white navbar-light"> 
    <ul class="navbar-nav">
      <li class="nav-item maincat" @click="emitValue(0); setActiveMainCategory();" :class="{ active: isMainCategory }">Всі </li>
      <li class="nav-item">Категорії:</li>
      </ul>
    <ul class="navbar-nav navbar-overflow">
      <li class="nav-item" v-for="category in categories" :key="category.id"
        @click="emitValue(category.id); setActive(category); markForRemoval(category)"
        :class="{ 'to-remove': isRemove && category.marked, 'active': activeCategory === category.id }">
        <a class="nav-link" href="#">{{ category.name }}
          <span v-if="isRemove">X</span> 
        </a>

      </li>
    </ul>
    <ul class="navbar-nav ml-auto"> <li class="nav-item">
        <button class="btn btn-primary" @click="showForm = true">+</button>
      </li>
      <li class="nav-item">
        <button class="btn btn-danger" @click="handleRemove">×</button>
      </li>
    </ul>

    <section v-if="showForm">
      <div class="input-group">
        <input type="text" class="form-control" v-model="categoryName" placeholder="Назва категорії">
        <button class="btn btn-primary" @click="addCategory">Додати</button>
      </div>
    </section>
  </nav>
</template>


<script>
export default {
  name: "CategoryComponent",
  data() {
    return {
      categories: [],
      showForm: false,
      categoryName: '',
      isRemove: false,
      marked: null,
      activeCategory: null,
      isMainCategory: false,
    };
  },
  mounted() {
    this.fetchCategories();
  },
  methods: {
    handleRemove() {
      this.isRemove = !this.isRemove;
    },
    setActive(category) {
      this.activeCategory = category.id;
      this.isMainCategory = false;
    },
    setActiveMainCategory() {
      this.activeCategory = null;
      this.isMainCategory = true;
    },
    markForRemoval(category) {
      if (this.isRemove) {
        this.deleteCategory(category.id);
        this.marked = null; // Reset marked item
      } else {
        this.marked = category.id;
      }
    },
    deleteCategory(id) {
      this.axios.delete(`http://crm-test.san-sanych.in.ua/api/categories/${id}`)
        .then(() => {
          // Remove from categories array
          this.categories = this.categories.filter(category => category.id !== id);
          this.isRemove = false; // Reset remove mode
        })
        .catch(error => {
          // ... handle potential errors
        });
    },
    addCategory() {
      this.axios.post('http://crm-test.san-sanych.in.ua/api/categories', {
        name: this.categoryName,
        text: this.categoryName
      })
        .then(() => {
          this.showForm = false; // Success: Hide the form
          this.categoryName = ''; // Reset input fields
          this.fetchCategories();
        })
        .catch(error => {
          // Handle potential errors here
          console.error('Error adding category:', error);
        });
    },
    emitValue(catId) {
      this.$emit('valueChanged', catId); // Emit with payload
    },
    async fetchCategories() {
      try {
        const response = await this.axios.get('http://crm-test.san-sanych.in.ua/api/categories');
        this.categories = response.data;
        // this.emitValue('valueChanged', response.data[0].id);
        // this.setActive(response.data[0])
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    },
    changeCategoryId() {
      this.categoryId = 0; // Directly set to 0 on click
    }
  }
};
</script>

<style scoped>
.maincat{
  margin-right: 10px;
}
.maincat.active{
  text-decoration: underline;
}
.navbar {
  border-bottom: 1px solid black;
  padding: 10px 0;
}

.navbar-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.navbar-item {
  margin: 0 10px;
}

.navbar-controls {
  display: flex;
}

.control-button {
  margin-left: 10px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 20px;
  line-height: 20px;
}

.control-button:first-child {
  color: blue;
}

.control-button:last-child {
  color: red;
}

.navbar {
  border-bottom: 1px solid black;
  padding: 10px 0;
}

.navbar-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.navbar-list {
  list-style: none;
  display: flex;
  padding: 0;
  margin: 0;
}

.navbar-item {
  margin: 0 10px;
}

.navbar-item.active{
  text-decoration: underline;
}

.navbar-controls {
  display: flex;
}

.control-button {
  margin-left: 10px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 20px;
  line-height: 20px;
}

.control-button:first-child {
  color: blue;
}

.control-button:last-child {
  color: red;
}

.navbar-overflow{
width: 100%;
overflow-x: auto;
margin-right: 40px;
}

</style>
