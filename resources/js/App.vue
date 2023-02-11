<template>
    <div>
        <nav class="navbar bg-light border-bottom navbar-light">
            <div class="container-fluid">
                <router-link class="navbar-brand me-auto" :to="{name: 'home'}">One</router-link>
                <router-link class="nav-link" :to="{name: 'two'}">Two</router-link>
            </div>
        </nav>
        <div class="container py-4 px-3 mx-auto">
            <router-view></router-view>
        </div>
    </div>
</template>

<script setup lang="ts">
import {RouterView} from 'vue-router'</script>
