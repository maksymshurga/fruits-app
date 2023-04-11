<template>
  <v-app class="container">
    <v-card>
      <v-card-title>
        Fruits
        <v-spacer></v-spacer>
        <v-text-field
          v-model="name"
          append-icon="mdi-magnify"
          label="Search by Name"
          single-line
          hide-details
        ></v-text-field>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="family"
          append-icon="mdi-magnify"
          label="Search by Family"
          single-line
          hide-details
        ></v-text-field>
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
              <th>Favorite</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, index) in fruits"
              :key="index"
            >
              <td>{{ (page - 1) * limit + index + 1 }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.family }}</td>
              <td>{{ item.orders }}</td>
              <td>{{ item.genus }}</td>
              <td>{{ item.calories }}</td>
              <td>{{ item.fat }}</td>
              <td>{{ item.sugar }}</td>
              <td>{{ item.carbohydrates }}</td>
              <td>{{ item.protein }}</td>
              <td>
                <v-checkbox
                  dense
                  v-model="item.favorite"
                  @click="toggleFavorite(item.id, item.favorite)"
                ></v-checkbox>
              </td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
      <div class="text-center">
        <v-pagination
          v-model="page"
          :length="totalPages"
          circle
        ></v-pagination>
        <span class="items-count">{{ (page - 1) * limit + 1 }} - {{ page * limit }} of {{ totalCount }}</span>
      </div>
    </v-card>
    <v-snackbar
      v-model="showNotification"
      :timeout="2000"
      top
    >
      {{ message }}
    </v-snackbar>
  </v-app>
</template>

<script>
import axios from '../plugins/axiosInstance'

export default {
  name: 'FruitsComponent',
  data () {
    return {
      fruits: [],
      name: '',
      family: '',
      page: 1,
      limit: 10,
      totalCount: 0,
      totalPages: 0,
      showNotification: false,
      message: ''
    }
  },
  mounted() {
    this.getFruitsData()
  },
  watch: {
    name: function() {
      this.getFruitsData()
    },
    family: function() {
      this.getFruitsData()
    },
    page: function() {
      this.getFruitsData()
    },
  },
  methods: {
    getFruitsData() {
      axios.get('/fruits', {
        params: { page: this.page, limit: this.limit, name: this.name, family: this.family }
      })
        .then(response => {
          this.fruits = response.data.fruits
          this.totalCount = response.data.meta.totalCount
          this.totalPages = response.data.meta.totalPages
        })
        .catch(error => {
          console.log(error)
        })
    },
    toggleFavorite(id, favorite) {
      axios.put(`/fruits/${id}`, { favorite })
        .then(response => {
          this.showNotification = true
          if (response.data.result) {
            this.message = favorite ? 'Added to Favorite!' : 'Removed from Favorite'
          } else {
            this.message = 'You can only add up to 10 fruits to favorites'
            const index = this.fruits.findIndex(item => item.id === id)
            this.fruits[index].favorite = false
          }
        })
        .catch(error => {
          console.log(error)
        })
    }
  }
}
</script>

<style scoped>
.container {
  width: 100%;
}
.items-count {
  float: right;
  position: relative;
  top: -32px;
  right: 20px;
}
</style>
