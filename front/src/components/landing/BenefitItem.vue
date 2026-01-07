<script setup>
defineProps({
  icon: {
    type: String,
    required: true
  },
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  }
})
</script>

<template>
  <div class="flex items-start space-x-3 md:space-x-4">
    <div class="w-10 h-10 md:w-12 md:h-12 bg-[#F5EEDC] rounded-lg flex items-center justify-center shrink-0 mt-1">
      <i :class="icon" class="text-green_pastel text-lg md:text-xl"></i>
    </div>
    <div>
      <h4 class="text-lg md:text-xl font-['Outfit'] font-semibold text-[#333333] mb-1 md:mb-2">
        {{ title }}
      </h4>
      <p class="text-sm md:text-base text-lighBlue">
        {{ description }}
      </p>
    </div>
  </div>
</template>
