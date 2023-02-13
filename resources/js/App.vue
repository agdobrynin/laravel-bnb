<template lang="pug">
div
    nav(class="navbar bg-light border-bottom navbar-light")
        div(class="container-fluid")
            router-link(class="navbar-brand me-auto" :to="{name: 'home'}") B&B
    div(class="container py-4 px-3 mx-auto")
        router-view
</template>
