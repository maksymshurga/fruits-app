<template>
  <v-app class="container">
    <v-card width="100%">
      <v-card-title>
        Favorites
      </v-card-title>
      <v-simple-table>
        <template v-slot:default>
          <thead>
            <tr>
              <th>No</th>
              <th>Name</th>
              <th>Family</th>
              <th>Order</th>
              <th>Genus</th>
              <th>Calories</th>
              <th>Fat</th>
              <th>Sugar</th>
              <th>Carbohydrates</th>
              <th>Protein</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, index) in favorites"
              :key="index"
            >
              <td>{{ index + 1 }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.family }}</td>
              <td>{{ item.orders }}</td>
              <td>{{ item.genus }}</td>
              <td>{{ item.calories }}</td>
              <td>{{ item.fat }}</td>
              <td>{{ item.sugar }}</td>
              <td>{{ item.carbohydrates }}</td>
              <td>{{ item.protein }}</td>
            </tr>
            <tr>
              <td colspan="5">Sum of Nutritions</td>
              <td>{{ getSumOfPropertyValue(favorites, 'calories') }}</td>
              <td>{{ getSumOfPropertyValue(favorites, 'fat') }}</td>
              <td>{{ getSumOfPropertyValue(favorites, 'sugar') }}</td>
              <td>{{ getSumOfPropertyValue(favorites, 'carbohydrates') }}</td>
              <td>{{ getSumOfPropertyValue(favorites, 'protein') }}</td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </v-card>
  </v-app>
</template>
  
<script>
import axios from '../plugins/axiosInstance'

export default {
  name: 'FavoritesComponent',
  data () {
    return {
      favorites: []
    }
  },
  mounted() {
    this.getFavoritesData()
  },
  methods: {
    getFavoritesData() {
      axios.get('/fruits/favorites')
        .then(response => {
          this.favorites = response.data
        })
        .catch(error => {
          console.log(error)
        })
    },
    getSumOfPropertyValue(arr, field) {
      return Math.round(arr.reduce((acc, obj) => acc + obj[field], 0) * 100) / 100;
    }
  }
}
</script>

<style scoped>
.container {
  width: 100%;
}
</style>
  