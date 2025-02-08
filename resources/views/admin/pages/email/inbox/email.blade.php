<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Inbox</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
}

.email-list {
    margin-top: 20px;
}

.email-item {
    padding: 15px;
    border-bottom: 1px solid #ddd;
    cursor: pointer;
    transition: background-color 0.3s;
}

.email-item.unread {
    background-color: #e3f2fd;
    font-weight: bold;
}

.email-item:hover {
    background-color: #f1f1f1;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 600px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

.modal-body {
    margin-top: 20px;
    white-space: pre-wrap;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 10px;
}
#email-modal img{
    width: 100px !important;
}
.refresh{
    font-size: 14px;
    background: #757575;
    padding: 7px 15px;
    border-radius: 7px;
    color: white;
    cursor: pointer;
}
.heading{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    margin-top: 15px;
    margin-bottom: 30px;
}
.heading .top{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.heading h1{
    margin-bottom: 0;
    margin-top: 0;
}
</style>
</head>
<body>
    <div class="container">
        <div class="heading">
            <div class="top">
                <h1>Email Inbox</h1>
                <span class="refresh">Refresh</span>
            </div>
            <span style="font-size: 12px">{{ request()->get("email") }}</span>
        </div>

        <div id="email-list" class="email-list">
        </div>
    </div>

    <!-- Modal for displaying full email -->
    <div id="email-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modal-subject"></h2>
            <p><strong>From:</strong> <span id="modal-from"></span></p>
            <p><strong>Date:</strong> <span id="modal-date"></span></p>
            <div id="modal-body" class="modal-body"></div>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$(document).ready(function () {
    const emailList = $('#email-list');
    const modal = $('#email-modal');
    const closeModal = $('.close');

    // Fetch emails from the backend
    const fetchEMails = () => {
        $.ajax({
            url: '/admin/email/fetch-email',
            method: 'POST',
            headers: {
                "X-CSRF-Token": "{{ csrf_token() }}"
            },
            data: {
                "email": '{{ request()->get("email") }}',
                "password": '{{ request()->get("password") }}',
            },
            success: function (emails) {
                emailList.html("");
                $(".refresh").text("Refresh");

                emails.forEach(email => {
                    const emailItem = $('<div>').addClass('email-item');
                    if (email.unread) {
                        emailItem.addClass('unread');
                    }

                    emailItem.html(`
                        <strong>${email.subject}</strong>
                        <p>From: ${email.from}</p>
                        <p>Date: ${email.date}</p>
                    `);

                    emailItem.on('click', function () {
                        openModal(email);
                        if (email.unread) {
                            markAsRead(email.id);
                            emailItem.removeClass('unread');
                        }
                    });

                    emailList.append(emailItem);
                });
            },
            error: function (xhr, status, error) {
                console.error('Error fetching emails:', error);
            }
        });
    }
    emailList.html("Loading...");
    fetchEMails();
    $(".refresh").click(function(){
        $(".refresh").text("Loading...");
        fetchEMails();
    });

    // Open modal with email details
    function openModal(email) {
        $('#modal-subject').text(email.subject);
        $('#modal-from').text(email.from);
        $('#modal-date').text(email.date);
        $('#modal-body').html(email.body);
        modal.css('display', 'block');
    }

    // Close modal
    closeModal.on('click', function () {
        modal.css('display', 'none');
    });

    $(window).on('click', function (event) {
        if (event.target === modal[0]) {
            modal.css('display', 'none');
        }
    });

    // Mark email as read using jQuery AJAX
    function markAsRead(emailId) {
        $.ajax({
            url: `/admin/email/mark-as-read/${emailId}`,
            method: 'POST',
            headers: {
                "X-CSRF-Token": "{{ csrf_token() }}"
            },
            data: {
                "email": '{{ request()->get("email") }}',
                "password": '{{ request()->get("password") }}',
            },
            success: function (response) {
                console.log('Marked as read:', response);
            },
            error: function (xhr, status, error) {
                console.error('Error marking as read:', error);
            }
        });
    }
});
</script>
</body>
</html>