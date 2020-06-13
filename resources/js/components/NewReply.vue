<template>
    <div>
        <div class="form-group">
            <textarea
                name="body"
                class="form-control"
                placeholder="Have something to say?"
                rows="5"
                required
                v-model="body"></textarea>
        </div>
        <button type="submit"
                class="btn btn-primary"
                @click="addReply">Post</button>

        <p class="text-center mt-3">Please <a href="{{ route('login') }}">sing in</a> to participate in this discussion.</p>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                body: '',
                endpoint: ''
            }
        },

        methods: {
            addReply() {
                axios.post(this.endpoint, { body: this.body}).then(response => {
                    this.body = '';

                    flash('Your reply has been posted.');

                    this.$emit('created', response.data);
                });
            }
        }
    }
</script>
