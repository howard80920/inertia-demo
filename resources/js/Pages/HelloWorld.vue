<template>
  <div class="container py-8">
    <alert v-if="$page.props.flash.success" class="shadow mb-6">{{ $page.props.flash.success }}</alert>
    <div class="space-x-3 pb-2 border-b border-purple-200">
      <inertia-link href="/" class="link">Hello world</inertia-link>
      <inertia-link href="/about" class="link">About</inertia-link>
    </div>
    <h1 class="text-2xl mt-2">Hello {{ name }}</h1>
    <icon icon="heroicons-outline:music-note" />
  </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Alert from '@/Components/Alert'

export default {
  layout: AppLayout,
  components: {
    Alert
  },
  metaInfo: {
    title: 'Hello world'
  },
  props: {
    name: String,
  },
}
</script>
