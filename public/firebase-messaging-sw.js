// Firebase SDK এবং আমাদের কনফিগারেশন ফাইল ইম্পোর্ট করা হচ্ছে
importScripts("https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js");
importScripts("/js/firebase-config.js");

// Firebase চালু করা হচ্ছে
if (!firebase.apps.length) {
    firebase.initializeApp(firebaseConfig);
}

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
    console.log('[SW] Background message received: ', payload);

    // payload থেকে নোটিফিকেশনের মূল ডেটা নেওয়া হচ্ছে
    const notificationData = payload.notification;
    
    // নোটিফিকেশনের টাইটেল এবং বডি সেট করা হচ্ছে
    const notificationTitle = notificationData.title;
    
    // নোটিফিকেশনের অপশনগুলো তৈরি করা হচ্ছে
    const notificationOptions = {
        body: notificationData.body,
        icon: '/assets/images/notification_icon.png', // আপনার ডিফল্ট আইকন
        
        // ** চূড়ান্ত পরিবর্তন: এখানে ইমেজ যোগ করা হচ্ছে **
        // যদি payload-এ ইমেজ URL থাকে, তাহলে সেটি ব্যবহার করা হবে
        image: notificationData.image 
    };

    // নোটিফিকেশনটি দেখানো হচ্ছে
    return self.registration.showNotification(notificationTitle, notificationOptions);
});