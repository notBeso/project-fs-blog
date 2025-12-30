<template>
    <div class="main-content" style="margin-bottom: 20px;">
        <p>Search Blogs</p>
        <div class="main-form-container" style="padding: 40px;">
            
            <form class="form-grid" @submit.prevent>
                <label for="blog-tittle" style="text-align: left;">Title:</label>
                <input class="search-bar" type="text" @keyup.enter="performSearch" placeholder="Search..." v-model="query" >
            </form>
            <button class="search-btn" type="button" @click="performSearch">Search</button>
        </div>
    </div>

    <div class="main-content" style="padding-bottom: 20px;">
        <p>List Blog</p>
        <div class="main-form-container">
            <div>{{ results.id }}</div>
            <table v-if="this.results.length" width="100%">
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
                    <tr v-for="blog in this.results" :key="blog.id">
                        <td>{{ blog.id }}</td>
                        <td>{{ blog.title }}</td>
                        <td>{{ blog.category }}</td>
                        <td>{{ blog.public }}</td>
                        <td>{{ blog.position }}</td>
                        <td>{{ blog.data_public }}</td>
                        <td>
                            <button class="edit-btn" @click="goDetail(blog.id)">Edit</button>
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
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 7fr;
        gap: 0; 
        height: fit-content;
    }


    .main-content {
        background-color: WhiteSmoke;
        padding:0;
        font-size: 30px;
        
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

    .search-btn {
        color: forestgreen;
        background-color: white;
        border: 1px solid forestgreen;
        font-size: 30px;
        padding: 5px 10px;
        border-radius: 5px;
        margin-top: 20px;
    }

    .search-bar {
        width: 100%;
        border: 1px solid gray;
        margin:8px 0 0 20px; 
        height: 30px;
        
    }

    input {
        font-size: large;
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
import { debounce } from 'lodash';

export default {
    data() {
        return {
        query: '',
        results: [],
        };
    },
    methods: {
        performSearch: debounce(function () {
            
            if (!this.query) {
                this.results = [];
                return;
            }
            axios.get(`http://localhost:8000/api/blogs/search?q=${encodeURIComponent(this.query)}`)
                .then(response => {
                this.results = response.data;
                })
                .catch(error => {
                console.error("There was an error with the search request:", error);
                });
        }, 300),

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
            this.$router.push(`/edit/${blogid}`);
        }
    }
};


</script>