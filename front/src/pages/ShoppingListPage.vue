<template>
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">{{ list?.title }}</h1>

    <div v-if="list?.items?.length === 0" class="text-gray-500">
      Aucun article
    </div>

    <ul class="space-y-3">
      <li
        v-for="item in list.items"
        :key="item.id"
        class="flex items-center gap-3"
      >
        <input
          type="checkbox"
          v-model="checked[item.id]"
          class="h-4 w-4"
        />

        <span
          :class="{
            'line-through text-gray-400': checked[item.id]
          }"
        >
          {{ item.name }}
        </span>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { useRoute } from "vue-router"
import api from "@/services/api"

const route = useRoute()
const list = ref(null)
const checked = ref({})

onMounted(async () => {
  const res = await api.get(`/shopping-lists/${route.params.id}`)
  list.value = res.data

  // init checkbox state
  res.data.items.forEach(item => {
    checked.value[item.id] = false
  })
})
</script>
