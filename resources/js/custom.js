const REQUIRED_WARNING_MESSAGE = "Vui lòng nhập trường này!";
const applyWarning = (el) => {
    const value = el.tagName == "INPUT" ? el.value : el.innerText || el.value;
    if ([null, "", undefined].includes(value)) {
        el.setCustomValidity(REQUIRED_WARNING_MESSAGE);
    } else {
        el.setCustomValidity("");
    }
};

(() => {
    document
        .querySelectorAll(`textarea, input[required]:not([type="hidden"])`)
        .forEach((el) => {
            applyWarning(el);
            el.addEventListener("input", () => applyWarning(el));
            el.addEventListener("change", () => applyWarning(el));
        });
})();
