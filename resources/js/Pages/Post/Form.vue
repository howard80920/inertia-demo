<template>
  <div class="py-6 md:py-8">
    <form @submit.prevent="submit" class="card card-main">
      <h1 class="text-3xl text-center">{{ pageTitle }}</h1>
      <div class="w-12 mt-1 mx-auto border-b-4 border-purple-400"></div>

      <div class="flex justify-center space-x-4 mt-6">
        <inertia-link v-if="isEdit" :href="`/posts/${post.id}`" class="link">
          <icon class="text-purple-500" icon="heroicons-outline:book-open" />
          <span>檢視文章</span>
        </inertia-link>
        <inertia-link href="/posts" class="link">
          <icon class="text-purple-500" icon="heroicons-outline:view-list" />
          <span>文章列表</span>
        </inertia-link>
      </div>

      <div class="grid gap-6 mt-6">
        <text-input v-model="form.title" :error="$page.props.errors.title" label="標題" ref="titleInput" autocomplete="off" />
        <markdown-input v-model="form.content" :error="$page.props.errors.content" label="內容" class="min-w-0" />
        <file-input v-model="form.thumbnail" :error="$page.props.errors.thumbnail" accept="image/*" label="分享預覽圖片" browseText="選擇圖片" />
        <img v-if="post.thumbnail" :src="post.thumbnail" class="max-w-xs rounded shadow">
        <div class="font-light mb-4">
          <label>
            <input type="checkbox" class="form-checkbox" v-model="form.published"> 發布文章
          </label>
        </div>
        <div class="flex items-center space-x-4">
          <loading-button :loading="loading" class="btn btn-purple">{{ btnText }}</loading-button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import TextInput from '@/Components/TextInput'
import TextareaInput from '@/Components/TextareaInput'
import FileInput from '@/Components/FileInput'
import LoadingButton from '@/Components/LoadingButton'
import MarkdownInput from '@/Components/MarkdownInput'

export default {
  layout: AppLayout,
  remember: 'form',
  metaInfo() {
    return {
      title: this.pageTitle
    }
  },
  components: {
    TextInput,
    TextareaInput,
    MarkdownInput,
    FileInput,
    LoadingButton
  },
  props: {
    post: Object
  },
  data() {
    return {
      form: {
        title: this.post.title,
        content: this.post.content,
        thumbnail: null,
        published: this.post.published
      },
      loading: false
    }
  },
  computed: {
    isEdit() {
      return Boolean(this.post.id)
    },
    pageTitle() {
      return this.isEdit ? '編輯文章' : '撰寫文章'
    },
    btnText() {
      return this.isEdit ? '更新文章' : '儲存文章'
    },
  },
  methods: {
    submit() {
      const data = new FormData()
      for (const key in this.form) {
        data.append(key, this.form[key] || '')
      }
      if (this.isEdit) data.append('_method', 'put')

      this.$inertia.post(this.isEdit ? `/posts/${this.post.id}` : '/posts', data, {
        onStart: () => this.loading = true,
        onFinish: () => this.loading = false,
        onSuccess: () => {
          if (! Object.keys(this.$page.props.errors).length) {
            this.form.thumbnail = null
          }
        }
      })
    }
  },
  mounted() {
    if (!this.isEdit) {
      this.$refs.titleInput.focus()
    }
  }
}
</script>
