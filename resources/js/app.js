import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

globalThis.confirmBeforeDelete = async ({
    event,
    title = "Bạn có chắc chắn?",
    text = "Xác nhận xóa!",
}) => {
    event.preventDefault();
    const { isConfirmed } = await Swal.fire({
        title,
        text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Xoá!",
        cancelButtonText: "Hủy",
    });

    if (isConfirmed) {
        event.target.submit();
    }
};

globalThis.confirmBeforeSave = async ({
    event,
    title = "Bạn có chắc chắn?",
    text = "Xác nhận thay đổi!",
}) => {
    event.preventDefault();
    const { isConfirmed } = await Swal.fire({
        title,
        text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Lưu!",
        cancelButtonText: "Hủy",
    });

    if (isConfirmed) {
        event.target.submit();
    }
};

globalThis.confirmBeforeCreate = async ({
    event,
    title = "Bạn có chắc chắn?",
    text = "Xác nhận thêm mới!",
}) => {
    event.preventDefault();
    const { isConfirmed } = await Swal.fire({
        title,
        text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Lưu!",
        cancelButtonText: "Hủy",
    });

    if (isConfirmed) {
        event.target.submit();
    }
};

const getQueryParams = (url) => {
    const query = new URLSearchParams(url.replace(/^.+\?/, "?"));
    return Object.fromEntries(query.entries());
};

globalThis.goTo = (url) => {
    const currentParams = getQueryParams(location.search);
    const targetParams = getQueryParams(url);
    const urlObject = new URL(url);
    for (const key in currentParams) {
        if (targetParams[key] !== undefined) continue;
        urlObject.searchParams.set(key, currentParams[key]);
    }
    location.href = urlObject.toString();
};
