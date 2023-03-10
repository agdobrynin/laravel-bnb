<template lang="pug">
div.card(aria-hidden="true")
    div.card-body
        slot
        h5.card-title.placeholder-glow
            span.placeholder.col-6.bg-primary
        p.card-text.placeholder-glow
            span.placeholder.col-7
            span.placeholder.col-4
            span.placeholder.col-8
        button.btn.btn-primary.disabled.placeholder.col-6(tabindex="-1")
</template>

