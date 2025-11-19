<script setup lang="ts">
    import { computed, ref } from 'vue';

    interface Iprops {
        modelValue: string,
        type :  'text' | 'email' | 'password' | 'number' | 'tel' | 'url' | 'date',
        name? : string,
        placeholder? : string,
        errorMessage? : string,
        title : string,
        icon : "pi-lock" | "pi-at" | "pi-user" | "pi-phone" | "pi-globe" | "pi-calendar"
    }

    const props = defineProps<Iprops>()

    const emit = defineEmits<{
        (e : 'update:modelValue', value: string) : void
    }>();

    const handleInput = (event: Event) => {
        const target = event.target as HTMLInputElement;
        emit('update:modelValue', target.value);
    } 

    const ShowPassword = ref(false);
    const CurrentType = computed(() => {
        if (props.type === 'password') {
            return ShowPassword.value ? 'text' : 'password';
        }
        return props.type;
    });

    const togglePasswordVisibility = () => {
       ShowPassword.value = !ShowPassword.value;
    };
</script>

<template>
    <label>
        <h4 class="text-md font-semibold mb-2">{{ props.title }}</h4>
        <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl w-full"
            :class="props.errorMessage ? 'border-red-500' : 'border-gray-200'"
        >
            <i :class="['pi',props.icon, 'text-gray-700']"></i>
        <input :type="CurrentType" :name="props.name" :placeholder="props.placeholder" :value="props.modelValue"
                class="bg-transparent outline-none flex-1 text-gray-700 placeholder-gray-400 text-sm"
                @input="handleInput"
            />
            <i v-if="props.type === 'password'" :class="['pi',ShowPassword ? 'pi-eye-slash' : 'pi-eye', 'text-gray-700','cursor-pointer']" @click="togglePasswordVisibility"></i>
        </div>
        <span v-if="!!props.errorMessage" class="text-red-500 font-bold mt-1 text-sm">{{ props.errorMessage }}</span>
    </label>
</template>