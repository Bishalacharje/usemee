const translations = {
  en: {
    title: "Welcome to My Website",
    description: "This is a simple custom language translator example.",
    button: "Click Here",
  },
  bn: {
    title: "আমার ওয়েবসাইটে স্বাগতম",
    description: "এটি একটি সহজ কাস্টম ভাষা অনুবাদক উদাহরণ।",
    button: "এখানে ক্লিক করুন",
  },
  hi: {
    title: "मेरी वेबसाइट पर आपका स्वागत है",
    description: "यह एक सरल कस्टम भाषा अनुवादक उदाहरण है।",
    button: "यहाँ क्लिक करें",
  },
};

function translatePage(language) {
  document.querySelectorAll("[data-translate]").forEach((element) => {
    const key = element.getAttribute("data-translate");
    element.textContent = translations[language][key];
  });
}

document
  .getElementById("language-select")
  .addEventListener("change", function () {
    translatePage(this.value);
  });

// Optional: Load default language
translatePage("en");
