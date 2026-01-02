<template>
    <div class="main-content">
        <p>Edit Blogs</p>
        <div class="main-form-container">
            
            <form>
                <label for="blog-tittle">Tiêu đề:</label>
                <input 
                    type="text" 
                    id="tittle" 
                    name="blog-tittle" 
                    v-model= 'item.title'>
            </form>

            <form>
                <label for="blog-describe">Mô tả ngắn:</label>
                <textarea 
                    id="describe" 
                    name="blog-describe" 
                    style="height: 100px;"
                    v-model="item.des"></textarea>
            </form>

            <form>
                <label for="blog-detail">Chi tiết:</label>
                <textarea 
                    id="detail" 
                    name="blog-detail" 
                    style="height: 250px;"
                    v-model="item.detail"></textarea>
            </form>

            <form @submit.prevent>
                <input
                    ref="fileInput"
                    type="file" 
                    style="border: none;"
                    @change="handleFileChange"
                    v-on:change="item.thumbs"
                    v-if="isRemoveThumbs"
                    accept="image/*"
                />

		<div v-else-if="item.thumbs_url">
			<label>Ảnh đại diện hiện tại
			<button @click="() => isRemoveThumbs = true">Xóa</button>
			</label>
                	<img :src="item.thumbs_url" v-if="item.thumbs_url" />
		</div>
            </form>

            <label for="blog-location">Vị trí:</label>    
            <form ref="myForm" style="display:flex;">
                <div v-for="loc in locations" :key="loc.id" style="width: 150px;display: flex;">
                  <input type="checkbox" v-model="item.position" :value="loc.id" style="display:flex; width: fit-content;">
                  <span style="display:flex;">{{ loc.label }}</span>
                </div>
            </form>

            <label for="blog-location">Public:</label>
            <form style="display:flex;">
                
                <input class="form-check-input" type="radio" value="true" v-model="item.public">
                <span class="form-check-label" for="inlineRadio1">Yes</span>
                
                <input class="form-check-input" type="radio" value="false" v-model="item.public">
                <span class="form-check-label" for="inlineRadio2">No</span>
                
            </form>

            <div class="double-form">
                <div class="form-slot">
                    <form>
                        <label for="blog-tittle">Loại:</label>
                        <select class="form-control" id="exampleFormControlSelect1" v-model="item.category">
                            <option disabled>-Choose a type-</option>
                            <option v-for="opt in options" >
                                {{ opt.label }}
                            </option>
                        </select>
                    </form>
                </div>
                <div class="form-slot">
                    <form>
                        <label for="blog-tittle">Date Public:</label>
                        <input type="date" id="myDate" name="selectedDate" v-model="item.data_public">
                    </form>
                </div>
            </div>
            
        </div>
        <button class="submit-btn" type="submit" @click="updateBlog">Update</button>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';
    import { RouterLink, useRoute, useRouter } from 'vue-router';
    const { $getLocations, $getOptions } = useNuxtApp()

    const title = ref('');
    const describe = ref('');
    const detail = ref('');
    const fileInput = ref(null)
    const selectedFile = ref(null)
    const publicity = ref('')
    const optType = ref('');
    const selectedLocation = ref([])

    const options = ref(await $getOptions())
    const locations = ref(await $getLocations())

    const DateSelect = ref('')

    const handleFileChange = (event) => {
        selectedFile.value = event.target.files[0]
    }

    const route = useRoute();
    const router = useRouter()
    
    const blogId = route.params.id;

    const item = ref((await axios.get(`http://localhost:8000/api/blogs/${blogId}`)).data)
    item.value.thumbs = null
    const isRemoveThumbs = ref(!item.value.thumbs_url)

    const initStatesForItem = () => {
        item.value.thumbs = null
        selectedFile.value = null
        isRemoveThumbs.value = !item.value.thumbs_url
    }

    const updateBlog = async () => {
        try {
	    const formData = new FormData()

            formData.append("title", item.value.title)
            formData.append("des", item.value.des)
            formData.append("detail", item.value.detail)
            formData.append("category", item.value.category)
            formData.append("public", item.value.public)
            formData.append("data_public", item.value.data_public)

	    item.value.position.forEach((pos) => formData.append("position[]", pos))
            formData.append("isRemoveThumbs", isRemoveThumbs.value)

	    if(isRemoveThumbs.value && selectedFile.value) {
		formData.append("thumbs", selectedFile.value)
	    }
            
            item.value = (await axios.put(`http://localhost:8000/api/blogs/${blogId}`, formData)).data;
	    alert('Success')
	    initStatesForItem()
        } catch (error) {
            alert('Error:', error)
        }
    }
</script>

<style scoped>
    .main-content {
        background-color: WhiteSmoke; /* DELETE LATER */
        /* overflow: scroll;
        overflow-x:hidden; */
        /* height: calc(100vh - 20px); */
        padding:0 0 20px 0;
    }

    .main-form-container {
        background-color: white;
        padding: 20px;
        text-align: left;
        /* overflow: scroll;
        overflow-x:hidden;
        overflow-y:hidden; */
    }
    
    p {
        text-align: left;
        margin: 0 0 10px 10px;
        font-size: 40px;
        font-weight: 500;
        padding: 0;
    }

    form {
        margin-bottom: 30px;
    }

    label {
        font-size: 25px;
        display: block;
        margin-bottom: 10px;
    }

    textarea{
        width: 98%;
        min-height: 100px;
        font-size: 25px;
        font-weight: 200;
        border: 1px solid gray;
        resize: vertical;
        overflow: scroll;
        overflow-x:hidden;
    }

    input{
        width: 98%;
        font-size: 25px;
        font-weight: 200;
        border: 1px solid gray;
    }

    select {
        width: 98%;
    }

    .double-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0; 
    }

    .form-slot {
        padding: auto;
        text-align: left;
    }

    .form-check-label {
        display: flex;
        margin-right: 2%;
        font-size: larger;
    }
    .form-check-input {
        width: fit-content;
    }

    .form-control {
        height: 35px;
        border: 1px solid gray;
        font-size: larger;
    }

    .submit-btn {
        border-radius: 5px;
        margin: 5px;
        padding: 6px 10px;
        border: none;
        color: white;
        margin-top: 15px;
    }

    .submit-btn {
        background-color: forestgreen;

    }
</style>
