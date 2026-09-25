@extends('layouts.mainlayout')

@section('style')
<style>
    .chat-wrapper { display: flex; height: 80vh; border: 1px solid #ddd; }
    .chat-sidebar { width: 300px; border-right: 1px solid #ddd; display: flex; flex-direction: column; }
    .chat-sidebar-header { display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #eee; }
    .chat-list { flex: 1; overflow-y: auto; }
    .chat-sidebar .client-item { padding: 12px 15px; cursor: pointer; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
    .chat-sidebar .client-item:hover, .chat-sidebar .client-item.active { background: #f1f5f9; }
    .chat-sidebar .client-item .last-msg { font-size: 12px; color: #888; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 180px; }
    .unread-badge { background: #2563eb; color: #fff; border-radius: 50%; min-width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; padding: 0 5px; }
    .chat-main { flex: 1; display: flex; flex-direction: column; }
    .chat-messages { flex: 1; overflow-y: auto; padding: 15px; background: #fafafa; display: flex; flex-direction: column; }
    .chat-bubble { max-width: 60%; padding: 8px 12px; border-radius: 10px; margin-bottom: 10px; word-wrap: break-word; }
    .chat-bubble.admin { background: #4f46e5; color: #fff; align-self: flex-end; }
    .chat-bubble.client { background: #e5e7eb; color: #111; align-self: flex-start; }
    .chat-bubble img { max-width: 220px; border-radius: 8px; display: block; }
    .chat-bubble video { max-width: 240px; border-radius: 8px; display: block; }
    .chat-bubble .file-link { display: flex; align-items: center; gap: 6px; text-decoration: underline; color: inherit; }
    .chat-input-box { display: flex; align-items: center; padding: 10px; border-top: 1px solid #ddd; gap: 8px; }
    .chat-input-box input[type=text] { flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 6px; }
    .load-more-clients { text-align: center; padding: 8px; color: #2563eb; cursor: pointer; font-size: 13px; }

    .new-chat-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 999; }
    .new-chat-box { background: #fff; width: 400px; margin: 60px auto; border-radius: 8px; padding: 15px; max-height: 70vh; display: flex; flex-direction: column; }
    .new-chat-box input { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 6px; margin-bottom: 10px; }
    .new-chat-box .results { overflow-y: auto; flex: 1; }
    .new-chat-box .client-row { padding: 10px; cursor: pointer; border-bottom: 1px solid #eee; }
    .new-chat-box .client-row:hover { background: #f1f5f9; }
    .date-separator { text-align: center; margin: 15px 0; }
    .date-separator span { background: #e5e7eb; color: #555; font-size: 12px; padding: 4px 12px; border-radius: 12px; }
    .chat-bubble .msg-time { font-size: 10px; opacity: 0.65; margin-top: 4px; text-align: right; }
</style>
@endsection

@section('content')

<div class="chat-wrapper">
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
           <strong>{{ __('messages.chat.chats') }}</strong>
             <button class="btn btn-sm btn-primary" onclick="openNewChatModal()">
                {{ __('messages.chat.new_chat') }}
            </button>
                </div>
        <div class="chat-list" id="clientList">
            <div class="p-2 text-muted">{{ __('messages.chat.loading') }}</div>
        </div>
        <div class="load-more-clients" id="loadMoreInbox" style="display:none;">{{ __('messages.chat.load_more') }}
</div>
    </div>

    <div class="chat-main">
        <div class="chat-messages" id="chatMessages">
            <div class="text-center text-muted mt-5">{{ __('messages.chat.select_chat') }}</div>
        </div>
        <div class="chat-input-box">
            <label for="fileInput" style="cursor:pointer; font-size:20px;">📎</label>
            <input type="file" id="fileInput" style="display:none;" disabled accept="image/*,video/*,.pdf,.doc,.docx">
                     <input type="text" id="messageInput" placeholder="{{ __('messages.chat.type_message') }}" disabled>
            <button class="btn btn-primary" id="sendBtn" disabled>{{ __('messages.chat.send') }}</button>
        </div>
        <div id="filePreviewBar" style="padding:0 10px 8px; font-size:12px; color:#555;"></div>
    </div>
</div>

<!-- New Chat Search Modal -->
<div class="new-chat-modal" id="newChatModal">
    <div class="new-chat-box">
        <input type="text" id="newChatSearch" placeholder="{{ __('messages.chat.search_client') }}">        <div class="results" id="newChatResults"></div>
        <button class="btn btn-sm btn-secondary mt-2" onclick="closeNewChatModal()">
                        {{ __('messages.chat.close') }}
        </button>
    </div>
</div>
<script>
    window.CHAT_TRANS = {
        loading:       @json(__('messages.chat.loading')),
        load_more:     @json(__('messages.chat.load_more')),
        select_chat:   @json(__('messages.chat.select_chat')),
        attached:      @json(__('messages.chat.attached')),
        download_file: @json(__('messages.chat.download_file')),
        today:         @json(__('messages.chat.today')),
        yesterday:     @json(__('messages.chat.yesterday')),
    };
</script>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import { getDatabase, ref, onChildAdded, onChildChanged, off } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-database.js";

const firebaseConfig = {
    apiKey: "AIzaSyBI_XzRwMY2iWlgZOlsl50y1BrQEQeKrWY",
    authDomain: "suteria-48f4d.firebaseapp.com",
    databaseURL: "https://suteria-48f4d-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "suteria-48f4d",
};
const T = window.CHAT_TRANS;
// date time
function formatDateLabel(dateStr) {
    const msgDate = new Date(dateStr); //.replace(' ', 'T'));
    const today = new Date();
    const yesterday = new Date();
    yesterday.setDate(today.getDate() - 1);

    const isSameDay = (a, b) => a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();

    if (isSameDay(msgDate, today)) return 'Today';
    if (isSameDay(msgDate, yesterday)) return 'Yesterday';

    const dd = String(msgDate.getDate()).padStart(2, '0');
    const mm = String(msgDate.getMonth() + 1).padStart(2, '0');
    const yyyy = msgDate.getFullYear();
    return `${dd}/${mm}/${yyyy}`;
}

function formatTimeLabel(dateStr) {
    const d = new Date(dateStr); //.replace(' ', 'T'));
    let hours = d.getHours();
    const minutes = String(d.getMinutes()).padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12;
    return `${hours}:${minutes} ${ampm}`;
}
//end date time
const app = initializeApp(firebaseConfig);
const db = getDatabase(app);
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
const ADMIN_ID = {{ auth('admin')->id() }};

let currentChatId = null;
let currentClientId = null;
let currentMessagesRef = null;
const renderedMessageIds = new Set();

let inboxPage = 1;
let inboxHasMore = true;
let inboxLoading = false;
const inboxItems = new Map(); // chatId -> item data (for reordering/badge updates)

let selectedFile = null;
let lastRenderedDate = null; // tracks last date-group shown in the currently open chat

const clientListEl = document.getElementById('clientList');
const chatMessagesEl = document.getElementById('chatMessages');
const messageInput = document.getElementById('messageInput');
const sendBtn = document.getElementById('sendBtn');
const fileInput = document.getElementById('fileInput');
const filePreviewBar = document.getElementById('filePreviewBar');
const loadMoreBtn = document.getElementById('loadMoreInbox');

/* ---------------- INBOX (existing chats, paginated) ---------------- */

async function loadInbox(reset = false) {
    if (inboxLoading || (!inboxHasMore && !reset)) return;
    inboxLoading = true;
     if (reset) {
        inboxPage = 1; inboxHasMore = true;
        inboxItems.clear();
        clientListEl.innerHTML = `<div class="p-2 text-muted">${T.loading}</div>`;
    }

    const res = await fetch(`/admin/chat/inbox?page=${inboxPage}`, { headers: { Accept: 'application/json' } });
    const data = await res.json();

     if (reset) clientListEl.innerHTML = '';

    data.data.forEach(item => {
        inboxItems.set(item.chat_id, item);
        renderInboxItem(item);
    });

    inboxHasMore = data.has_more;
    loadMoreBtn.style.display = inboxHasMore ? 'block' : 'none';
    loadMoreBtn.textContent   = T.load_more;
    inboxPage++;
    inboxLoading = false;
}

function renderInboxItem(item, prepend = false) {
    let el = document.getElementById('chat-item-' + item.chat_id);
    if (!el) {
        el = document.createElement('div');
        el.className = 'client-item';
        el.id = 'chat-item-' + item.chat_id;
        el.addEventListener('click', () => openChat(item.chat_id, item.client_id, item.client_name));
        if (prepend && clientListEl.firstChild) clientListEl.insertBefore(el, clientListEl.firstChild);
        else clientListEl.appendChild(el);
    } else if (prepend) {
        clientListEl.insertBefore(el, clientListEl.firstChild);
    }

    el.innerHTML = `
        <div>
            <div>${item.client_name}</div>
            <div class="last-msg">${item.last_message || ''}</div>
        </div>
        ${item.unread_count > 0 ? `<span class="unread-badge">${item.unread_count}</span>` : ''}
    `;
    if (item.chat_id === currentChatId) el.classList.add('active');
}

loadMoreBtn.addEventListener('click', () => loadInbox());

/* ---------------- Single Firebase inbox listener (scales to 1000s of chats) ---------------- */

const inboxRef = ref(db, `inbox/admin/${ADMIN_ID}`);
onChildAdded(inboxRef, handleInboxPush);
onChildChanged(inboxRef, handleInboxPush);

// function handleInboxPush(snapshot) {
//     const update = snapshot.val(); // { chat_id, last_message, last_message_at, unread_count }
//     const existing = inboxItems.get(update.chat_id);

//     if (!existing) return; // chat not loaded in current page yet — will show correct data once its page loads

//     existing.last_message = update.last_message;
//     existing.unread_count = (update.chat_id === currentChatId) ? 0 : update.unread_count;
//     inboxItems.set(update.chat_id, existing);

//     renderInboxItem(existing, true); // move to top
// }

function handleInboxPush(snapshot) {
    const update = snapshot.val(); // { chat_id, party_id, party_name, last_message, last_message_at, unread_count }
    let existing = inboxItems.get(update.chat_id);

    if (!existing) {
        // Brand new chat that wasn't in the sidebar yet — build it fresh
        existing = {
            chat_id: update.chat_id,
            client_id: update.party_id,
            client_name: update.party_name,
            last_message: update.last_message,
            unread_count: update.unread_count,
        };
    } else {
        existing.last_message = update.last_message;
        existing.unread_count = (update.chat_id === currentChatId) ? 0 : update.unread_count;
    }

    inboxItems.set(update.chat_id, existing);
    renderInboxItem(existing, true); // move/insert to top
}

/* ---------------- New Chat search modal ---------------- */

window.openNewChatModal = function () {
    document.getElementById('newChatModal').style.display = 'block';
    searchNewChatClients('');
};
window.closeNewChatModal = function () {
    document.getElementById('newChatModal').style.display = 'none';
};

let searchDebounce = null;
document.getElementById('newChatSearch').addEventListener('input', (e) => {
    clearTimeout(searchDebounce);
    searchDebounce = setTimeout(() => searchNewChatClients(e.target.value), 350);
});

async function searchNewChatClients(term) {
    const res = await fetch(`/admin/get-clients-for-chat?search=${encodeURIComponent(term)}`, { headers: { Accept: 'application/json' } });
    const data = await res.json();
    const resultsEl = document.getElementById('newChatResults');
    resultsEl.innerHTML = '';
    data.data.forEach(client => {
        const row = document.createElement('div');
        row.className = 'client-row';
        row.textContent = client.first_name || client.email_id || ('Client #' + client.id);
        row.addEventListener('click', async () => {
            closeNewChatModal();
            const res = await fetch('/admin/chat/start', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({ receiver_id: client.id })
            });
            const chatData = await res.json();
            const item = { chat_id: chatData.chat.id, client_id: client.id, client_name: client.first_name, last_message: '', unread_count: 0 };
            inboxItems.set(item.chat_id, item);
            renderInboxItem(item, true);
            openChat(item.chat_id, client.id, client.first_name);
        });
        resultsEl.appendChild(row);
    });
}

/* ---------------- Open a chat ---------------- */

async function openChat(chatId, clientId, clientName) {
    currentChatId = chatId;
    currentClientId = clientId;
    lastRenderedDate = null;
    document.querySelectorAll('.client-item').forEach(i => i.classList.remove('active'));
    const el = document.getElementById('chat-item-' + chatId);
    if (el) el.classList.add('active');

    fileInput.disabled = false;
    messageInput.disabled = false;
    sendBtn.disabled = false;
    renderedMessageIds.clear();

    await loadMessages(chatId);
    listenRealtime(chatId);

    // mark as read
    await fetch(`/admin/chat/${chatId}/mark-read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, Accept: 'application/json' }
    });
    const item = inboxItems.get(chatId);
    if (item) { item.unread_count = 0; renderInboxItem(item); }
}

async function loadMessages(chatId) {
    const res = await fetch('/admin/chat/' + chatId + '/messages', { headers: { Accept: 'application/json' } });
    const data = await res.json();
    chatMessagesEl.innerHTML = '';
    data.data.forEach(msg => { renderedMessageIds.add(msg.id); renderMessage(msg); });
    scrollToBottom();
}

// function listenRealtime(chatId) {
//     if (currentMessagesRef) off(currentMessagesRef);
//     currentMessagesRef = ref(db, 'chats/' + chatId + '/messages');
//     onChildAdded(currentMessagesRef, (snapshot) => {
//         const msg = snapshot.val();
//         if (renderedMessageIds.has(msg.id)) return;
//         renderedMessageIds.add(msg.id);
//         renderMessage(msg);
//         scrollToBottom();
//     });
// }

function listenRealtime(chatId) {
    if (currentMessagesRef) off(currentMessagesRef);
    currentMessagesRef = ref(db, 'chats/' + chatId + '/messages');
    onChildAdded(currentMessagesRef, (snapshot) => {
        const msg = snapshot.val();
        if (renderedMessageIds.has(msg.id)) return;
        renderedMessageIds.add(msg.id);
        renderMessage(msg);
        scrollToBottom();

        // FIX 2: if a client message arrives while this chat is the one currently open,
        // immediately sync "read" status to the DB too — not just visually.
        if (msg.sender_type === 'client' && currentChatId === chatId) {
            fetch(`/admin/chat/${chatId}/mark-read`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, Accept: 'application/json' }
            });
            const item = inboxItems.get(chatId);
            if (item) { item.unread_count = 0; renderInboxItem(item); }
        }
    });
}

function renderMessage(msg) {
    const msgDateLabel = formatDateLabel(msg.created_at);

    if (msgDateLabel !== lastRenderedDate) {
        const sep = document.createElement('div');
        sep.className = 'date-separator';
        sep.innerHTML = `<span>${msgDateLabel}</span>`;
        chatMessagesEl.appendChild(sep);
        lastRenderedDate = msgDateLabel;
    }

    const bubble = document.createElement('div');
    bubble.className = 'chat-bubble ' + (msg.sender_type === 'admin' ? 'admin' : 'client');

    let inner = '';
    if (msg.file_type === 'image') {
        inner += `<img src="${msg.file_path}">`;
    } else if (msg.file_type === 'video') {
        inner += `<video src="${msg.file_path}" controls></video>`;
    } else if (msg.file_type === 'pdf' || msg.file_type === 'doc') {
        inner += `<a class="file-link" href="${msg.file_path}" target="_blank">📄 ${msg.file_name || 'Download file'}</a>`;
    }
    if (msg.message) inner += `<div>${msg.message}</div>`;
    inner += `<div class="msg-time">${formatTimeLabel(msg.created_at)}</div>`;

    bubble.innerHTML = inner;
    chatMessagesEl.appendChild(bubble);
}


function scrollToBottom() { chatMessagesEl.scrollTop = chatMessagesEl.scrollHeight; }

/* ---------------- File select preview ---------------- */

fileInput.addEventListener('change', () => {
    selectedFile = fileInput.files[0] || null;
    filePreviewBar.textContent = selectedFile ? `Attached: ${selectedFile.name}` : '';
});

/* ---------------- Send message (text and/or file) ---------------- */

async function sendMessage() {
    const text = messageInput.value.trim();
    if (!text && !selectedFile) return;
    if (!currentChatId) return;

    const formData = new FormData();
    formData.append('chat_id', currentChatId);
    if (text) formData.append('message', text);
    if (selectedFile) formData.append('file', selectedFile);

    messageInput.value = '';
    fileInput.value = '';
    filePreviewBar.textContent = '';
    selectedFile = null;

    await fetch('/admin/chat/send', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, Accept: 'application/json' }, // no Content-Type — browser sets multipart boundary
        body: formData
    });
    // realtime listener renders it back automatically
}

sendBtn.addEventListener('click', sendMessage);
messageInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') sendMessage(); });

loadInbox(true);
</script>

@endsection