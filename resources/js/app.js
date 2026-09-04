import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('chatThread', (config) => ({
    conversationId: config.conversationId,
    pollUrl: config.pollUrl,
    sendUrl: config.sendUrl,
    currentUserId: config.currentUserId,
    messages: config.initialMessages,
    lastId: config.initialMessages.length ? Math.max(...config.initialMessages.map((m) => m.id)) : 0,
    body: '',
    imageUrl: '',
    sending: false,
    pollTimer: null,

    init() {
        this.scrollToBottom();
        this.pollTimer = setInterval(() => this.poll(), 4000);
        this.$watch('messages', () => this.$nextTick(() => this.scrollToBottom()));
    },

    destroy() {
        clearInterval(this.pollTimer);
    },

    scrollToBottom() {
        const el = this.$refs.scroller;
        if (el) {
            el.scrollTop = el.scrollHeight;
        }
    },

    async send() {
        const body = this.body.trim();
        const imageUrl = this.imageUrl.trim();

        if (!body && !imageUrl) {
            return;
        }

        const tempId = `temp-${Date.now()}`;

        this.messages.push({
            id: tempId,
            sender_id: this.currentUserId,
            mine: true,
            body,
            image_url: imageUrl || null,
            created_at: new Date().toISOString(),
            pending: true,
        });

        this.body = '';
        this.imageUrl = '';
        this.sending = true;

        try {
            const response = await fetch(this.sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ body, image_url: imageUrl || null }),
            });

            if (!response.ok) {
                throw new Error('send-failed');
            }

            const data = await response.json();
            const index = this.messages.findIndex((m) => m.id === tempId);

            if (index !== -1) {
                this.messages.splice(index, 1, data.message);
            }

            this.lastId = Math.max(this.lastId, data.message.id);
        } catch (error) {
            const index = this.messages.findIndex((m) => m.id === tempId);

            if (index !== -1) {
                this.messages[index].failed = true;
            }
        } finally {
            this.sending = false;
        }
    },

    async poll() {
        try {
            const response = await fetch(`${this.pollUrl}?after_id=${this.lastId}`, {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();

            data.messages.forEach((message) => {
                if (!this.messages.some((existing) => existing.id === message.id)) {
                    this.messages.push(message);
                }
            });

            if (data.messages.length) {
                this.lastId = Math.max(this.lastId, ...data.messages.map((m) => m.id));
            }
        } catch (error) {
            // silent retry on the next tick — transient network hiccups shouldn't surface to the user
        }
    },
}));

Alpine.data('unreadBadge', (url) => ({
    count: 0,

    async refresh() {
        try {
            const response = await fetch(url, { headers: { Accept: 'application/json' } });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            this.count = data.count;
        } catch (error) {
            // keep the last known count on transient network errors
        }
    },

    init() {
        this.refresh();
        setInterval(() => this.refresh(), 15000);
    },
}));

Alpine.start();
