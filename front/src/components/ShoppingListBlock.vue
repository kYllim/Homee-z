<template>
  <div class="w-full flex justify-center">
    <div class="bg-white w-full max-w-4xl rounded-3xl shadow-xl p-8">

      <!-- Title -->
      <div class="flex items-center justify-between mb-10">
        <h1 class="text-3xl font-extrabold text-gray-800">
          Listes de courses
        </h1>

        <button
          @click="showModal = true"
          class="h-10 w-10 flex items-center justify-center bg-[#9CBFA2] text-white rounded-full shadow hover:scale-105"
        >
          +
        </button>
      </div>

      <!-- Lists -->
      <div class="space-y-8">
        <div
          v-for="list in shoppingLists"
          :key="list.id"
          class="bg-gray-50 rounded-2xl p-6 shadow-sm hover:shadow-md transition"
        >

          <!-- Header -->
          <div class="flex justify-between items-center mb-3">
            <h2 class="text-xl font-semibold text-gray-800">
              {{ list.title }}
            </h2>

            <div class="flex items-center gap-3">
              <!-- Status -->
              <span
                class="text-xs px-3 py-1 rounded-full font-medium"
                :class="statusClass(list.status)"
              >
                {{ list.status }}
              </span>

              <!-- Menu -->
              <div class="relative">
                <button @click="toggleMenu(list.id)">⋮</button>

                <div
                  v-if="openMenu === list.id"
                  class="absolute right-0 mt-2 w-40 bg-white border rounded-xl shadow z-10"
                >
                  <button
                    class="menu-item"
                    @click="changeStatus(list.id, 'active')"
                  >
                    🟢 Active
                  </button>
                  <button
                    class="menu-item"
                    @click="changeStatus(list.id, 'draft')"
                  >
                    🟡 Draft
                  </button>
                  <button
                    class="menu-item"
                    @click="changeStatus(list.id, 'generated')"
                  >
                    ⚪ Générée
                  </button>

                  <hr />

                  <button
                    class="menu-item text-red-500"
                    @click="deleteList(list.id)"
                  >
                    🗑 Supprimer
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Info -->
          <p class="text-sm text-gray-500 mb-4">
            {{ list.items.length }} article(s) •
            {{ formatDate(list.createdAt) }}
          </p>

          <!-- Articles -->
          <div class="space-y-2">
            <div
              v-for="item in visibleItems(list)"
              :key="item.id"
              class="flex items-center gap-2"
            >
              <input
                type="checkbox"
                v-model="item.checked"
              />

              <span
                :class="{
                  'line-through text-gray-400': item.checked,
                  'text-gray-700': !item.checked
                }"
              >
                {{ item.name }}
              </span>
            </div>
          </div>

          <!-- Voir tous -->
          <button
            v-if="list.items.length > 3"
            @click="toggleExpand(list.id)"
            class="text-sm text-[#7CA886] mt-3 font-medium"
          >
            {{ expandedLists.includes(list.id)
              ? 'Réduire'
              : 'Voir tous les articles'
            }}
          </button>
        </div>
      </div>

      <!-- Modal -->
      <CreateListModal
        v-if="showModal"
        @close="showModal = false"
        @created="loadLists"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import CreateListModal from './CreateListModal.vue'

const shoppingLists = ref([])
const openMenu = ref(null)
const expandedLists = ref([])
const showModal = ref(false)

/* ------------------ DATA ------------------ */

const loadLists = async () => {
  const res = await api.get('/shopping-lists')
  shoppingLists.value = res.data.map(list => ({
    ...list,
    items: list.items?.map(i => ({ ...i, checked: false })) || []
  }))
}

onMounted(loadLists)

/* ------------------ UI ------------------ */

const toggleMenu = id => {
  openMenu.value = openMenu.value === id ? null : id
}

const toggleExpand = id => {
  expandedLists.value.includes(id)
    ? expandedLists.value = expandedLists.value.filter(i => i !== id)
    : expandedLists.value.push(id)
}

const visibleItems = list => {
  return expandedLists.value.includes(list.id)
    ? list.items
    : list.items.slice(0, 3)
}

/* ------------------ API ACTIONS ------------------ */

const changeStatus = async (id, status) => {
  await api.patch(`/shopping-lists/${id}`, { status })
  await loadLists()
  openMenu.value = null
}

const deleteList = async id => {
  if (!confirm('Supprimer cette liste ?')) return
  await api.delete(`/shopping-lists/${id}`)
  await loadLists()
  openMenu.value = null
}

/* ------------------ HELPERS ------------------ */

const formatDate = d => new Date(d).toLocaleDateString('fr-FR')

const statusClass = status => ({
  active: 'bg-green-200 text-green-700',
  draft: 'bg-yellow-100 text-yellow-700',
  generated: 'bg-gray-300 text-gray-700'
}[status])
</script>

<style scoped>
.menu-item {
  width: 100%;
  padding: 8px 12px;
  text-align: left;
  font-size: 14px;
}
.menu-item:hover {
  background: #f3f4f6;
}
</style>
