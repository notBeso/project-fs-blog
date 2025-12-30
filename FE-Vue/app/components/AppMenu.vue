
<template>
    <div>
        <ul class="menu-bar">
            <li>
                <NuxtLink to="/">
                    <img src="./img/list.png"></img>
                    List
                </NuxtLink>
            </li>
            <li>
                <NuxtLink to="/new">
                    <img src="./img/plus.png" @click="editMode"></img>
                    New
                </NuxtLink>
            </li>
            <li>
                <NuxtLink to="/search">
                    <img src="./img/loupe.png"></img>
                    Search
                </NuxtLink>
            </li>
        </ul>
    </div>
</template>

<style>
    .menu-bar{
       min-height: 100vh;
    }

    ul {
        list-style: none;
        text-align: left;
        padding-left: 20px;
    }

    a {
        text-decoration: none;
        color: black;
        font-size: 150%;
    }

    li {
        gap: 20px;
        margin-bottom: 30px;
    }
    
    img {
        margin-right: 20px;
        height: 90%;
    }
</style>

<script>


</script>