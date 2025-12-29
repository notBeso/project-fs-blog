<template>
    <div class="main-content">
        <p>List Blog</p>
        <div class="main-form-container">
            <table v-if="blogs.length" width="100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Tin</th>
                        <th>Loại</th>
                        <th>Trạng thái</th>
                        <th>Vị trí</th>
                        <th>Ngày public</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="blog in blogs" :key="blog.id">
                        <td>{{ blog.id }}</td>
                        <td>{{ blog.title }}</td>
                        <td>{{ blog.category }}</td>
                        <td>{{ blog.public }}</td>
                        <td>{{ blog.position }}</td>
                        <td>{{ blog.data_pubblic }}</td>
                        <td>
                            <!-- <button class="edit-btn" type="button">Edit</button> -->
                            <button class="edit-btn" @click="goDetail(blog.id)">Edit</button>
                            <!-- <NuxtLink class="edit-btn" :to="{ name: 'Edit', params: { id: blog.id } }">
                                Edit
                            </NuxtLink> -->
                           
                            <!-- <a href="route('blogs.edit', blog.id)" class="edit-btn">Edit</a> -->
                            <!-- <button class="edit-btn" to="{ name: 'Edit', params: { id: blog.id } }">
                                Edit
                            </button> -->
                        </td>
                        <td>
                            <button to="/" class="del-btn" @click="deleteItem(blog.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
    .main-content {
        background-color: WhiteSmoke;
        padding:0 0 20px 0;
    }

    .main-form-container {
        background-color: white;
    }
    
    p {
        text-align: left;
        margin: 0 0 10px 10px;
        font-size: 40px;
        font-weight: 500;
        padding: 0;
    }
    
    table {
        border-collapse: collapse;
        /* max-height: 50px; */
        overflow: scroll;
        overflow-x: hidden;
    }

    th {
        font-size: smaller;
        border: 1px solid lightgray;
        gap: 0;
    }

    td {
        font-size: smaller;
        border: 1px solid lightgray;
        gap: 0;
    }

    .edit-btn {
        color: dodgerblue;
        background-color: white;
        border: 1px solid dodgerblue;
        font-size: small;
        padding: 5px 10px;
        border-radius: 5px;
    }

    
    .del-btn {
        color: red;
        background-color: white;
        border: 1px solid red;
        font-size: small;
        padding: 5px 10px;
        border-radius: 5px;
    }
</style>

<script>
import axios from 'axios';

import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
// import Vue from 'vue';
// import VueRouter from 'vue-router';
// Vue.use(VueRouter);
const router = useRouter()


export default {
    data() {
        return {
            blogs: [],
        };
    },

    methods: {
        async deleteItem(id) {
            if (confirm('Are you sure you want to delete this item?' + id)) {
                try {
                    await axios.delete(`http://localhost:8000/api/blogs/${id}/delete`);
                    this.blogs = this.blogs.filter(blog => blog.id !== id);
                    alert('Item deleted successfully!');
                } 
                catch (error) {
                    console.error('Error deleting item:', error);
                    alert('Failed to delete item.');
                }
                
            }
        },

        async goDetail(blogid) {
            alert(router + '  click ' + blogid);
            await router.push({ name: 'EP', params: { id: blogid } });
            // alert('done')
            this.$router.push(`/edit/${blogid}`);
        }
        
    },
    
        async created() {
            try {
            const response = await axios.get('http://localhost:8000/api/blogs')
            this.blogs = response.data
            } catch (error) {
            console.error('Error fetching blogs:', error)
            }
        },

        
}
</script>