<script setup lang="ts">
import { ref, computed } from 'vue';

interface IOption {
  label: string;
  value: string;
}

interface IProps {
  modelValue: string;
  title: string;
  placeholder?: string;
  errorMessage?: string;
  options: IOption[] | Record<string, string | number>;
  name?: string;
  icon?: string;
}

const props = defineProps<IProps>();
const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>();

const isOpen = ref(false);

const optionsList = computed<IOption[]>(() => {
  if (Array.isArray(props.options)) return props.options;
  return Object.entries(props.options).map(([k, v]) => {
    // If keys look like enum keys (ALL_CAPS or ALL_CAPS_WITH_UNDERSCORES),
    // use the value as the emitted value (e.g. PersonRoleEnum where k='CHILD', v='Child').
    const isEnumKey = /^[A-Z0-9_]+$/.test(k);
    return { label: String(v), value: String(isEnumKey ? v : k) };
  });
});

const selectedLabel = computed(() => {
  const found = optionsList.value.find(o => o.value === props.modelValue);
  return found?.label ?? props.placeholder ?? 'Select...';
});

const toggleDropdown = () => isOpen.value = !isOpen.value;

const selectOption = (value: string) => {
  emit('update:modelValue', value);
  isOpen.value = false;
};

const closeDropdown = () => {
  isOpen.value = false;
};
</script>

<template>
  <label class="w-full relative" v-click-outside="closeDropdown">
    <h4 class="text-md font-semibold mb-2">{{ props.title }}</h4>
    <div
      class="flex items-center gap-3 bg-gray-50 border rounded-xl w-full px-4 py-3 cursor-pointer relative"
      :class="props.errorMessage ? 'border-red-500' : 'border-gray-200'"
      @click="toggleDropdown"
    >
      <i v-if="props.icon" :class="['pi', props.icon, 'text-gray-700']"></i>
      <span class="flex-1 text-gray-700 text-sm">{{ selectedLabel }}</span>
      <i :class="['pi', isOpen ? 'pi-chevron-up' : 'pi-chevron-down', 'text-gray-700']"></i>
    </div>

    <div
      v-if="isOpen"
      class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-auto"
    >
      <div
        v-for="option in optionsList"
        :key="option.value"
        @click="selectOption(option.value)"
        class="px-4 py-3 hover:bg-blue-100 cursor-pointer text-gray-700 text-sm"
      >
        {{ option.label }}
      </div>
    </div>

    <span v-if="props.errorMessage" class="text-red-500 font-bold mt-1 text-sm block">{{ props.errorMessage }}</span>
  </label>
</template>
