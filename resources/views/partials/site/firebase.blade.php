{{--
    Firebase Analytics — moved here verbatim from the original home.blade.php.
    Pending investigation: project "ag-interior-design" appears to belong to the
    codebase this site was derived from. Do not remove without approval.
    Can be switched off with MK_FIREBASE_ANALYTICS=false.
--}}
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-app.js";
    import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-analytics.js";

    const firebaseConfig = {
        apiKey: "AIzaSyBI1i9DRNjLAEl-y_Hsq3hLiZpbJYUxjUY",
        authDomain: "ag-interior-design.firebaseapp.com",
        projectId: "ag-interior-design",
        storageBucket: "ag-interior-design.firebasestorage.app",
        messagingSenderId: "261319120347",
        appId: "1:261319120347:web:623c3beb5eb6f06b5943fe",
        measurementId: "G-BCK71P6YMT"
    };

    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);
</script>
