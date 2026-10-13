const btnComment = document.querySelector('#btn-comment');
const btnLike = document.querySelector('#btn-like');

const inputContent = document.querySelector('#comment-content');
const commentList = document.querySelector('.comment-list');
const likeCount = document.querySelector('.like-count');

btnComment.addEventListener('click', async function () {
    const id = this.dataset.productId;
    const data = { content: inputContent.value };
    const url = `${basePath}/api/products/${id}/comments`;
    const response = await fetch(url, {
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data)
    });
    const result = await response.json();
    commentList.replaceChildren();
    result.data.forEach(element => {
        const commentHTML = `<div class="border rounded p-3 mb-3"><span class="fs-6 text-secondary">${element.created_at}</span> - ${element.content}</div>`;
        commentList.innerHTML += commentHTML;
    });
    inputContent.value = '';
});

let productIds = [];
if(localStorage.getItem('productIds')) {
    productIds = JSON.parse(localStorage.getItem('productIds'));
}

btnLike.addEventListener('click', async function () {
    const id = this.dataset.productId;
    let url = '';
    if(productIds.includes(id) == false) {
        url = `${basePath}/api/products/${id}/likes`;
        productIds.push(id);
    }
    else {
        url = `${basePath}/api/products/${id}/unlikes`;
        productIds.splice(productIds.indexOf(id), 1);
    }

    const response = await fetch(url, {
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
        },
    });
    const result = await response.json();
    likeCount.textContent = result;
    localStorage.setItem('productIds', JSON.stringify(productIds));
});