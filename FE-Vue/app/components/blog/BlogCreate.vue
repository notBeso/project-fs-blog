<template>
    <div class="main-content">
        <p>New Blogs</p>
        <div class="main-form-container">
            <form>
                <label for="blog-tittle">Tiêu đề:</label>
                <input 
                    type="text" 
                    id="tittle" 
                    name="blog-tittle" 
                    v-model="title">
            </form>

            <form>
                <label for="blog-describe">Mô tả ngắn:</label>
                <textarea 
                    id="describe" 
                    name="blog-describe" 
                    style="height: 100px;"
                    v-model="describe"></textarea>
            </form>

            <form>
                <label for="blog-detail">Chi tiết:</label>
                <textarea 
                    id="detail" 
                    name="blog-detail" 
                    style="height: 250px;"
                    v-model="detail"></textarea>
            </form>

            <form>
                <input
                    ref="fileInput"
                    type="file" 
		    accept="image/*"
                    style="border: none;"
                    @change="handleFileChange"
                />
            </form>

            <label for="blog-location">Vị trí:</label>    
            <form ref="myForm" style="display:flex;">
                <div v-for="item in locations" :key="item.id" style="width: 150px;display: flex;">
                  <input type="checkbox" v-model="selectedLocation" :value="item.id" style="display:flex; width: fit-content;">
                  <span style="display:flex;">{{ item.label }}</span>
                </div>
            </form>

            <label for="blog-location">Public:</label>
            <form style="display:flex;">
                
                <input class="form-check-input" type="radio" value="true" v-model="publicity">
                <span class="form-check-label" for="inlineRadio1">Yes</span>
                
                <input class="form-check-input" type="radio" value="false" v-model="publicity">
                <span class="form-check-label" for="inlineRadio2">No</span>
                
            </form>

            <div class="double-form">
                <div class="form-slot">
                    <form>
                        <label for="blog-tittle">Loại:</label>
                        <select class="form-control" id="exampleFormControlSelect1" v-model="optType">
                            <option disabled>-Choose a type-</option>
                            <option v-for="option in options" >
                                {{ option.label }}
                            </option>
                        </select>
                    </form>
                </div>
                <div class="form-slot">
                    <form>
                        <label for="blog-tittle">Date Public:</label>
                        <input type="date" id="myDate" name="selectedDate" v-model="DateSelect">
                    </form>
                </div>
            </div>
            
        </div>
        <!-- <button class="submit-btn" type="submit">{{ store.isEdit ? 'Update' : 'Create' }}</button> -->
        <button class="submit-btn" type="submit" @click="addBlog">Create</button>
        
        <button class="clear-btn" @click="clearBox">Clear</button>
    </div>
</template>

<script setup>
    import axios from 'axios'
    import { ref } from 'vue'

    const { $getLocations, $getOptions } = useNuxtApp()

    const title = ref('');
    const describe = ref('');
    const detail = ref('');
    const fileInput = ref(null)
    const selectedFile = ref(null)
    const publicity = ref('')
    const optType = ref('');
    const selectedLocation = ref([])
    const DateSelect = ref('')

    const options = ref(await $getOptions())
    const locations = ref(await $getLocations())

    const handleFileChange = (event) => {
        selectedFile.value = event.target.files[0]
    }

    const clearBox = () => {
        title.value = ''
        describe.value = ''
        detail.value = ''
        
        if (fileInput.value) {
            fileInput.value.value = ''
        }

        selectedFile.value = null

        publicity.value = ''
        selectedLocation.value = []
        optType.value = ''
        DateSelect.value = ''
    }

    const addBlog = async () => {
        try {
            const formData = new FormData();

            if(selectedFile.value) {
            	formData.append('thumbs', selectedFile.value)
	    }

	    formData.append("title",  title.value)
            formData.append("des",  describe.value)
            formData.append("detail",  detail.value)
            formData.append("category",  optType.value)
            formData.append("public",  publicity.value)
            formData.append("data_public",  DateSelect.value)

	    selectedLocation.value.forEach(location => formData.append("position[]",  location))
 
            const response = await fetch('http://localhost:8000/api/blogs/create', {
            	method: 'POST',
            	body: formData
            })
	    
	    clearBox()
	    alert('Success!')
        } catch (error) {
            alert('Error:', error)
        }
    }
</script>

<style scoped>
    .main-content {
        background-color: WhiteSmoke;
        padding:0 0 20px 0;
    }

    .main-form-container {
        background-color: white;
        padding: 20px;
        text-align: left;
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

    .submit-btn , .clear-btn {
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

    .clear-btn {
        background-color: royalblue;
    }
</style>
