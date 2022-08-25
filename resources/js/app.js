import Vue from 'vue'
import VueMeta from 'vue-meta'
import { InertiaLink, createInertiaApp } from '@inertiajs/inertia-vue'
import { InertiaProgress } from '@inertiajs/progress'
import PurgeIconsVue from 'purge-icons-vue'
import mavonEditor from 'mavon-editor'
import 'mavon-editor/dist/css/index.css'
import 'mavon-editor/dist/markdown/github-markdown.min.css'
import 'mavon-editor/dist/highlightjs/styles/atom-one-dark.min.css'

InertiaProgress.init({
  color: '#ac94fa'
})


Vue.use(mavonEditor)

Vue.use(VueMeta)
Vue.use(PurgeIconsVue)
Vue.component('InertiaLink', InertiaLink);

const appName = document.title

createInertiaApp({
  resolve: name => import(`./Pages/${name}`),
  setup({ el, App, props, plugin }) {
    Vue.use(plugin)

    new Vue({
      metaInfo: {
        titleTemplate: title => title ? `${title} - ${appName}` : appName
      },
      render: h => h(App, props),
    }).$mount(el)
  },
})
