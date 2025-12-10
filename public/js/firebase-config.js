// ================================================================
//      Firebase Web Push Notification Configuration File
// ================================================================
// এই একটি মাত্র ফাইলে আপনার Firebase সংক্রান্ত সকল তথ্য থাকবে।

const firebaseConfig = {
    apiKey: "AIzaSyCEwQpcNU0l7vi3P8Y_xhJHdGrxvDyzwjA",
  authDomain: "notification-1449b.firebaseapp.com",
  projectId: "notification-1449b",
  storageBucket: "notification-1449b.firebasestorage.app",
  messagingSenderId: "710248063843",
  appId: "1:710248063843:web:0c93384667f684734e6956"
};

// আপনার Firebase প্রজেক্টের VAPID কী
const firebaseVapidKey = 'BIjXNM1fQlWY_yr2DowATDo2yfmq8JfahW_9byEMej9xfUPV3RTREmeirOBbn1yjsRc8ylvNs3L9pkxv0sg1jzY';

// নোটিফিকেশন বাটনে ক্লিক করলে যে মেসেজ দেখানো হবে
const notificationBenefitMessage = `
🔔 Get Notified Instantly! 🔔

Enable notifications to receive:
• Instant alerts for new matches.
• Updates on match results.
• Special offers and announcements from OX FF TOUR.

Click again to allow. You can manage this anytime in your browser settings.
`;