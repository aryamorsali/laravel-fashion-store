@if (session('toast-error'))
    <style>
        .custom-toast {
            position: fixed;
            top: 130px;
            right: 20px;
            background: linear-gradient(135deg, #ef4444, #cf2020);
            color: #fff;
            padding: 14px 18px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            z-index: 9999;
            animation: toastIn .4s ease;
        }

        .custom-toast .close-btn {
            margin-left: 10px;
            cursor: pointer;
            font-size: 18px;
            opacity: .8;
        }

        .custom-toast .close-btn:hover {
            opacity: 1;
        }

        @keyframes toastIn {
            from {
                transform: translateX(40px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-link {
            color: #fff;
            font-weight: 600;
            text-decoration: underline;
        }

        .toast-link:hover {
            color: #d1fae5;
        }
    </style>

    <div class="custom-toast" id="toast">
        <span>{!! session('toast-error') !!}</span>
        <span class="close-btn" onclick="document.getElementById('toast').remove()">×</span>
    </div>

    <script>
        setTimeout(() => {
            let toast = document.getElementById('toast');
            if (toast) toast.remove();
        }, 7000);
    </script>
@endif
