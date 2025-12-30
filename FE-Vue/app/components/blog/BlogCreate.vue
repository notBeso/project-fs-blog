<template>
    <div class="main-content">
        <p>New Blogs</p>
        <div class="main-form-container">
            <form>
                <label for="blog-tittle">Tiêu đề:</label>
                <textarea 
                    type="text" 
                    id="tittle" 
                    name="blog-tittle" 
                    style="height: 30px;"
                    v-model="title"></textarea>
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
                <span class="form-check-label" >Yes</span>
                
                <input class="form-check-input" type="radio" value="false" v-model="publicity">
                <span class="form-check-label" >No</span>
                
            </form>

            <div class="double-form">
                <div class="form-slot">
                    <form>
                        <label for="blog-tittle">Loại:</label>
                        <select class="form-control" id="exampleFormControlSelect1" v-model="optType" style="width: 80%;">
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
                        <input type="date" id="myDate" name="selectedDate" v-model="DateSelect" style="width: 80%;">
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
    import { ref } from 'vue';
    import axios from 'axios';

    const title = ref('');
    const describe = ref('');
    const detail = ref('');
    const fileInput = ref(null);
    const selectedFile = ref(null);
    const publicity = ref('');
    const optType = ref('');
    const selectedLocation = ref([]);
    const DateSelect = ref('');

    const options = [
        { value: '1', label: 'Kinh Doanh' },
        { value: '2', label: 'Giải Trí' },
        { value: '3', label: 'Thế Giới' },
        { value: '4', label: 'Thời Sự' },
    ];

    const locations = [
        { id: '1', label: 'Việt Nam' },
        { id: '2', label: 'Châu Á' },
        { id: '3', label: 'Châu Âu' },
        { id: '4', label: 'Châu Mỹ' },
    ];
    
    const handleFileChange = (event) => {
        selectedFile.value = event.target.files[0]
    }

    const clearBox = () => {
        alert('click')
        title.value = '';
        describe.value = '';
        detail.value = '';
        
        if (fileInput.value) {
            fileInput.value.value = '';
        }

        selectedFile.value = null;

        publicity.value = '';
        selectedLocation.value = [];
        optType.value = '';
        DateSelect.value = '';
    }

    const addBlog = async () => {
        alert('CREATE')
        try {
            const blog = {
                title: title.value,
                des: describe.value,
                detail: detail.value,
                category:optType.value,
                public:publicity.value,
                data_public:DateSelect.value,
                position: selectedLocation.value,
                thumbs: selectedFile.value,
            }
            const response = await axios.post(`http://localhost:8000/api/blogs/create`, blog);
            console.log('User added successfully');
        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>

<style scoped>
    .main-content {
        background-color: WhiteSmoke;
        padding:0;
        font-size: 30px;
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
        width: 90%;
        min-height: 30px;
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
        border-radius: 2px;
        padding: 6px 10px;
        border: none;
        color: white;
        margin: 15px 0;
    }

    .submit-btn {
        background-color: forestgreen;
        margin-right: 10px;
    }

    .clear-btn {
        background-color: royalblue;
    }
</style>