<template>
  <inertia-link :href="href" :method="method" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 focus:bg-gray-100" v-on="$listeners">
    <template v-if="icon">
      <span class="iconify mr-2" :data-icon="icon"></span>
      <slot />
    </template>
    <slot v-else />
  </inertia-link>
</template>

<script>
export default {
  props: {
    href: {
      type: String,
      required: true
    },
    icon: String,
    method: String,
  }
}
</script>
