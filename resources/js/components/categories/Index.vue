<template>
  <div class="navbar">
    <div class="navbar-container">
      <div class="navbar-item">Всі</div>
      <div class="navbar-item">Категорії:</div>
      <ul class="navbar-list">
        <li class="navbar-item">Сантехніка</li>
        <li class="navbar-item">Електрика</li>
        <li class="navbar-item">Дом. майстер</li>
      </ul>
      <div class="navbar-controls">
        <button class="control-button">+</button>
        <button class="control-button">×</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "CategoryComponent"
  // Vue component options
};
</script>

<style scoped>
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


</style>
