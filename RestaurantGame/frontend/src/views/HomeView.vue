<template>
  <div class="home">
    <h1>Bienvenue dans le restaurant</h1>
    <button @click="fetchData">Charger les plats</button>
    <ul v-if="dishes.length">
      <li v-for="dish in dishes" :key="dish.id">{{ dish.name }}</li>
    </ul>
  </div>
</template>

<script>
import apiClient from "@/api";

export default {
  data() {
    return {
      dishes: [],
    };
  },
  methods: {
    async fetchData() {
      try {
        const response = await apiClient.get("/dishes");
        this.dishes = response.data;
      } catch (error) {
        console.error("Erreur lors du chargement des plats", error);
      }
    },
  },
};
</script>

<style scoped>
.home {
  text-align: center;
  padding: 20px;
}
</style>
