<template>
  <div class="flex flex-col min-h-[100dvh] text-white">

    <!-- App bar -->
    <header class="sticky top-0 z-20 pt-[env(safe-area-inset-top)] bg-[rgba(6,19,117,0.72)] backdrop-blur-md border-b border-white/10">
      <div class="flex items-center gap-3 h-14 max-w-[760px] mx-auto px-4">
        <a :href="options.home_url || '/'" class="shrink-0 flex items-center justify-center w-9 h-9 -ml-1 rounded-full hover:bg-white/10 transition" aria-label="Zur Startseite">
          <img :src="options.logo_url" alt="" class="w-6 h-6">
        </a>
        <div class="flex-1 min-w-0 leading-tight">
          <div class="text-[17px] font-semibold tracking-tight">Zeteo</div>
          <div class="text-xs text-white/60 truncate">Suche und du wirst finden</div>
        </div>
        <button
          v-if="messages.length && !streaming"
          type="button"
          class="shrink-0 flex items-center justify-center w-10 h-10 -mr-2 p-0 rounded-full bg-transparent border-0 text-white hover:bg-white/10 active:bg-white/20 transition cursor-pointer"
          aria-label="Neues Gespräch"
          title="Neues Gespräch"
          @click="clearHistory"
        >
          <svg class="w-[22px] h-[22px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9" /><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" /></svg>
        </button>
      </div>
    </header>

    <main class="flex-1 flex flex-col w-full max-w-[760px] mx-auto px-4 pb-[calc(10rem+env(safe-area-inset-bottom))]">

      <!-- Welcome state -->
      <div v-if="messages.length === 0" class="flex-1 flex flex-col items-center justify-center text-center py-8">
        <div class="flex items-center justify-center w-20 h-20 mb-5 rounded-[28px] bg-white/10 ring-1 ring-white/20 shadow-2xl shadow-black/30">
          <img :src="options.logo_url" alt="" class="w-11 h-11">
        </div>
        <h1 class="m-0 mb-2 text-4xl font-bold tracking-tight text-white">Zeteo</h1>
        <p class="m-0 mb-1 text-lg text-white/85">Suche und du wirst finden.</p>
        <p class="m-0 mb-8 text-sm text-white/55">KI-Antworten aus unserem Archiv von {{ recordingCount }} Videos</p>

        <div class="grid gap-2 w-full max-w-md">
          <button
            v-for="chip in exampleChips"
            :key="chip"
            type="button"
            class="group flex items-center gap-3 w-full px-4 py-3 rounded-2xl border border-white/10 bg-white/[0.07] text-left text-[15px] leading-snug text-white cursor-pointer transition hover:bg-white/[0.12] active:scale-[0.98]"
            @click="sendMessage(chip)"
          >
            <span class="flex-1">{{ chip }}</span>
            <svg class="shrink-0 w-4 h-4 text-white/40 transition group-hover:translate-x-0.5 group-hover:text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="m13 6 6 6-6 6" /></svg>
          </button>
        </div>
      </div>

      <!-- Conversation -->
      <div v-else class="flex flex-col gap-5 pt-5">
        <JoZeteoMessage
          v-for="(msg, i) in messages"
          :key="i"
          :ref="i === messages.length - 1 ? 'lastMessage' : undefined"
          :role="msg.role"
          :text="msg.text"
          :sources="msg.sources"
          :loading="msg.loading"
        />
      </div>

    </main>

    <!-- Composer -->
    <div ref="composer" class="fixed inset-x-0 bottom-0 z-20 px-3 pt-6 pb-[calc(0.5rem+env(safe-area-inset-bottom))] bg-gradient-to-t from-[#061375] via-[#061375] to-transparent">
      <div class="max-w-[760px] mx-auto">
        <div class="flex items-end gap-1 p-1.5 rounded-[26px] bg-white shadow-2xl shadow-black/40">
          <textarea
            ref="input"
            v-model="inputText"
            class="flex-1 min-w-0 resize-none border-0 bg-transparent px-3 py-2 text-base leading-6 text-gray-900 placeholder:text-gray-400 overflow-y-auto focus:outline-none focus:ring-0"
            :placeholder="messages.length ? 'Weitere Frage stellen…' : 'Stelle eine Frage…'"
            rows="1"
            enterkeyhint="send"
            @keydown="onKeydown"
            @input="autoGrow"
          />
          <button
            type="button"
            class="shrink-0 flex items-center justify-center w-10 h-10 p-0 rounded-full border-0 bg-[#0b5ee5] text-white cursor-pointer transition hover:bg-[#0d38ad] active:scale-95 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-default disabled:active:scale-100"
            :disabled="!inputText.trim() || streaming"
            aria-label="Senden"
            @click="sendMessage(inputText)"
          >
            <span v-if="streaming" class="c-spinner c-spinner--small" />
            <svg v-else class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5" /><path d="m5 12 7-7 7 7" /></svg>
          </button>
        </div>
        <p class="m-0 mt-2 text-[11px] leading-tight text-white/45 text-center">
          Zeteo ist eine KI und kann Fehler machen. Keine medizinische oder seelsorgerliche Beratung.
        </p>
      </div>
    </div>

  </div>
</template>

<script>
import JoStudyCenter from './JoStudyCenter.vue'
import JoZeteoMessage from './JoZeteoMessage.vue'

/**
 * App-like Zeteo chat. Reuses all chat logic (streaming, history, memory)
 * from JoStudyCenter and only swaps the template.
 */
export default {
  extends: JoStudyCenter,
  components: { JoZeteoMessage },
  mounted () {
    if (this.messages.length) {
      this.$nextTick(() => window.scrollTo(0, document.documentElement.scrollHeight))
    }
  },
  methods: {
    // Account for the sticky app bar and fixed composer when deciding
    // whether the latest message is visible.
    scrollLastMessageIntoView () {
      if (this._scrollRAF) return
      this._scrollRAF = requestAnimationFrame(() => {
        this._scrollRAF = null
        const el = this.$refs.lastMessage?.[0]?.$el
        if (!el) return
        const top = el.getBoundingClientRect().top
        const bottomLimit = window.innerHeight - (this.$refs.composer?.offsetHeight || 0)
        if (top >= 64 && top < bottomLimit - 48) return
        el.scrollIntoView({ behavior: 'smooth', block: 'start' })
      })
    },
  },
}
</script>
