<template>
    <div>
        <div class="d-flex align-items-center">
            <img :src="avatar" width="50" height="50" class="mr-2">

            <h1 v-text="user.name" class="mb-0"></h1>
        </div>

        <form v-if="canUpdate" method="POST" enctype="multipart/form-data" class="mt-2">
            <image-upload name="avatar" class="mr-1" @loaded="onLoad"></image-upload>
        </form>
    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue';

    export default {
        props: ['user'],

        components: { ImageUpload },

        data() {
            return {
                avatar: this.user.avatar_path
            };
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            }
        },

        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;
                this.persist(avatar.file);
            },

            persist(file) {
                let data = new FormData();

                data.append('avatar', file);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                .then(() => flash('Avatar uploaded!'));
            }
        }
    }
</script>
