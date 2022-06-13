<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <router-link :to="{ name: 'home' }" class="navbar-brand"
            >Fudex Task</router-link>
            <button
                class="navbar-toggler"
                data-toggle="collapse"
                data-target="#navbarCollapse"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="navbarCollapse" class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <router-link
                            class="nav-link"
                            data-toggle="collapse"
                            :to="{ name: 'home' }"
                        >
                            Home
                        </router-link>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        watch: {
            $route() {
                $("#navbarCollapse").collapse("hide");
            },
        },
        mounted(){
            // axios.defaults.headers.common['Authorization'] = 'Bearer '.this.$store.getters['userModule/authedToken'];
            axios.defaults.headers.common['Accept'] = 'application/json';
            axios.defaults.baseURL = 'http://127.0.0.1:8000/api/';
        }
    };
</script>
