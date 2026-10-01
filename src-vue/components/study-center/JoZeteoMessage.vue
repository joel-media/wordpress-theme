<template>
  <div class="scroll-mt-20">

    <!-- User bubble -->
    <div v-if="role === 'user'" class="flex justify-end">
      <div class="max-w-[85%] px-4 py-2.5 rounded-[20px] rounded-br-md bg-white/15 text-white text-[15px] leading-6 whitespace-pre-wrap break-words">
        {{ text }}
      </div>
    </div>

    <!-- Assistant -->
    <template v-else>
      <div v-if="loading" class="inline-flex px-4 py-3.5 rounded-[20px] rounded-bl-md bg-white">
        <div class="c-study-dots flex gap-1">
          <span class="block w-2 h-2 rounded-full bg-gray-400" />
          <span class="block w-2 h-2 rounded-full bg-gray-400" />
          <span class="block w-2 h-2 rounded-full bg-gray-400" />
        </div>
      </div>

      <div
        v-else
        class="c-study-content px-4 py-3 rounded-[20px] rounded-bl-md bg-white text-gray-800 text-[15px] leading-relaxed shadow-xl shadow-black/20 break-words"
        v-html="renderedHtml"
        @click="onCiteClick"
      />

      <!-- Sources carousel -->
      <div v-if="sources && sources.length" class="mt-3">
        <div class="mb-2 px-1 text-[11px] font-semibold uppercase tracking-wider text-white/50">
          Quellen · {{ sources.length }}
        </div>
        <div class="c-zeteo-carousel flex gap-3 -mx-4 px-4 pb-1 overflow-x-auto snap-x snap-mandatory scroll-px-4">
          <JoZeteoSource
            v-for="source in sources"
            :key="source.ref"
            :source="source"
          />
        </div>
      </div>
    </template>

  </div>
</template>

<script>
import JoStudyCenterMessage from './JoStudyCenterMessage.vue'
import JoZeteoSource from './JoZeteoSource.vue'

/** Reuses markdown + citation logic from JoStudyCenterMessage. */
export default {
  extends: JoStudyCenterMessage,
  components: { JoZeteoSource },
}
</script>
