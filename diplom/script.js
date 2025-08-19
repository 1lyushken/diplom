const sendBtn = document.getElementById('send-btn');
const sendBtnChat = document.getElementById('send-btn-chat');
const saveRootCauseBtn = document.getElementById('save-root-cause-btn');
const inputBox = document.getElementById('input-box');
const chatBox = document.getElementById('chat-box');
const jobTitle = document.getElementById('job-title');
const equipmentName = document.getElementById('equipment-name');
const issueDescription = document.getElementById('issue-description');
const shopName = document.getElementById('shop-name');

let prompts = [];
let modelResponse = ''; // Переменная для хранения ответа модели
let isFirstMessage = true; // Флаг, который отслеживает, отправлено ли первое сообщение

sendBtn.addEventListener('click', () => {
    const job = jobTitle.value.trim();
    const equipment = equipmentName.value.trim();
    const issue = issueDescription.value.trim();
    const shop = shopName.value.trim();

    if (job && equipment && issue && shop) {
        if (isFirstMessage) {
            prompts = [
    `Нужно сделать каскад промтов для поиска решения проблемы или поиска причин поломки с помощью метода «5 почему». Ты должен задавать вопросы о том, что поломалось и выдавать возможные причины проблемы, поломки. Я буду как работник проверять твои версии возможных причин проблем или поломок и указывать какой вариант верный. На основе моего варианта ответа ты будешь выбирать наиболее точные варианты ошибок и причины их появления. В данном случае рассмотрим, что я являюсь ${job} цеха ${shop}. и у меня не работает ${equipment}. Проблема: ${issue}.`
];
        

            chatBox.innerHTML = '';
            chatBox.innerHTML += `<p><b>Вы:</b> Должность: ${job}, Оборудование: ${equipment}, Проблема: ${issue}, Цех: ${shop}</p>`;

            // Отправляем запрос по форме
            sendPromptToModel(prompts[0]);
            console.log("Отправляемый промт:", prompts[0]);


            // Устанавливаем флаг, что первое сообщение отправлено
            isFirstMessage = false;
        }
    } else {
        alert('Пожалуйста, заполните все поля.');
    }
});

sendBtnChat.addEventListener('click', () => {
    const message = inputBox.value.trim();
    if (message) {
        chatBox.innerHTML += `<p><b>Вы:</b> ${message}</p>`;
        inputBox.value = '';

        prompts.push(message);
        sendPromptToModel(prompts.join(' '));
    }
});

// Обработчик кнопки "Сохранить корневую причину"
saveRootCauseBtn.addEventListener('click', () => {
    if (modelResponse.trim() === '') {
        alert('Ответ модели отсутствует. Невозможно сохранить корневую причину.');
    } else {
        const job = jobTitle.value.trim();
        const equipment = equipmentName.value.trim();
        const issue = issueDescription.value.trim();
        const shop = shopName.value.trim();

        // Отправка данных на сервер для сохранения в базе данных
        const formData = new FormData();
        formData.append('job', job);
        formData.append('equipment', equipment);
        formData.append('issue', issue);
        formData.append('workshop', shop);
        formData.append('root_cause', modelResponse);

        fetch('save_root_cause.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Корневая причина успешно сохранена!');
            } else {
                alert('Ошибка при сохранении. Попробуйте снова.');
            }
        })
        .catch(error => {
            console.error('Ошибка сети. Попробуйте снова.', error);
        });
    }
});

function sendPromptToModel(prompt) {
    fetch('http://127.0.0.1:5000/generate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ prompt: prompt })
    })
    .then(response => response.json())
    .then(data => {
        // Проверяем, что сервер вернул правильный ответ
        if (data.response) {
            modelResponse = data.response.trim();
            if (modelResponse) {
                chatBox.innerHTML += `<p><b>Модель:</b> ${modelResponse}</p>`;
                // Включаем кнопку "Сохранить корневую причину", так как ответ от модели есть
                saveRootCauseBtn.disabled = false;
            } else {
                chatBox.innerHTML += `<p><b>Модель:</b> Ответ пустой или ошибка</p>`;
                // Отключаем кнопку, если ответа нет
                saveRootCauseBtn.disabled = true;
            }
        } else {
            // Если сервер не вернул поле "response", это ошибка
            chatBox.innerHTML += `<p><b>Модель:</b> Ошибка на сервере: отсутствует поле "response"</p>`;
            saveRootCauseBtn.disabled = true;
        }
        chatBox.scrollTop = chatBox.scrollHeight;
    })
    .catch(error => {
        console.error('Ошибка при запросе к серверу:', error);
        chatBox.innerHTML += `<p><b>Модель:</b> Ошибка запроса</p>`;
    });
}

const startNewChatBtn = document.getElementById('start-new-chat-btn');

// Обработчик для кнопки "Начать новый чат"
startNewChatBtn.addEventListener('click', () => {
    // Очистка всех полей ввода
    jobTitle.value = '';
    equipmentName.value = '';
    issueDescription.value = '';
    shopName.value = '';
    inputBox.value = '';

    // Очистка чата
    chatBox.innerHTML = '';  // Очистка содержимого чата

    // Отключение кнопки "Сохранить корневую причину"
    saveRootCauseBtn.disabled = true;

    // Очищаем промты и ответ от модели
    prompts = [];
    modelResponse = '';

    // Сбрасываем флаг для первого сообщения
    isFirstMessage = true;
});
