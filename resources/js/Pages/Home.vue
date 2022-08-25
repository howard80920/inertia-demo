<template>
  <div class="py-6 md:py-8">
    <alert v-if="$page.props.flash.success" class="shadow mb-6">{{ $page.props.flash.success }}</alert>

    <div class="card card-main">
      <post-list :posts="posts" />
    </div>
  </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import PostList from '@/Lightning/PostList'
import Alert from '@/Components/Alert'

export default {
  layout: AppLayout,
  components: {
    Alert,
    PostList
  },
  props: {
    posts: Object
  }
}
</script>
