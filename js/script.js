"use sctict";

window.addEventListener('load', () => {
    let wrapper = document.querySelector('#wrapper');
    let formAuth = document.querySelector('.form-auth');
    let inputLogin = document.querySelector('.form-auth .login');
    let inputPassword = document.querySelector('.form-auth .password');

    let formReg = document.querySelector('.form-reg');
    let inputLoginReg = document.querySelector('.form-reg .login');
    let inputPasswordReg = document.querySelector('.form-reg .password-input');
    let inputPasswordCheckReg = document.querySelector('.form-reg .check-password-input');
    let passErrorMessage = document.querySelector('.form-reg .password .error-message');

    // функция показывает сообщение.
    function showSuccessMessage(container, message) {
        let successMessageTemplate = document.querySelector('#successful-message-template').content;
        let successMessage = successMessageTemplate.querySelector('.successful-message');
        successMessage.textContent = message;
        container.appendChild(successMessage);
    }

    // валидация полей с паролем
    if (inputPasswordCheckReg) {
        inputPasswordCheckReg.addEventListener('change', (e) => {
            if (inputPasswordReg.value != inputPasswordCheckReg.value) {
                passErrorMessage.textContent = 'Пароли не совпадают';
                inputPasswordReg.classList.add('error');
                inputPasswordCheckReg.classList.add('error');
            } else {
                if (inputPasswordReg.classList.contains('error')) {
                    inputPasswordReg.classList.remove('error');
                    inputPasswordCheckReg.classList.remove('error');
                    passErrorMessage.textContent = '';
                }
            }
        })
    }



    let responseObj = {};
    let responseObjForReg = {};


    // работа с регистрацией
    if (formReg) {
        formReg.addEventListener('submit', (e) => {
            e.preventDefault();

            // удаляю сведения об ошибке у заполняемого поля
            let formRegInput = document.querySelectorAll('input');
            formRegInput.forEach((value, key) => {
                formRegInput[key].addEventListener('input', (e) => {
                    formRegInput[key].classList.remove('error');
                    console.log(formRegInput[key]);

                    if (formRegInput[key].nextElementSibling) {
                        formRegInput[key].nextElementSibling.textContent = '';
                        console.log(formRegInput[key].nextElementSibling);
                    } else if (formRegInput[key].parentElement.parentElement.lastElementChild) {
                        formRegInput[key].parentElement.parentElement.lastElementChild.textContent = '';
                        console.log(formRegInput[key].nextElementSibling);
                    }
                    if (formRegInput[key].getAttribute('name') === 'gender') {
                        formRegInput[key].parentElement.parentElement.lastElementChild.textContent = '';
                    }

                });
            });


            let dataForm = new FormData(formReg);
            let fdObject = {};
            dataForm.forEach((value, key) => {
                fdObject[key] = value;
            });

            console.log(fdObject);
            let jsonToServer = JSON.stringify(fdObject);

            let request = new XMLHttpRequest();
            request.open('POST', 'backend.php');
            request.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            request.send(jsonToServer);

            request.addEventListener('load', () => {
                if (request.status === 200) {
                    let responseFromServer = JSON.parse(request.response);
                    if (!responseFromServer.error_status) {
                        window.location.href = "/personal/";
                    } else {
                        //код обработки ошибок
                        for (let key in responseFromServer) {
                            if (key === 'error_status') {
                                continue;
                            }
                            if (responseFromServer[key].error) {
                                let element = document.querySelector(`.${key}`);
                                let elementInput = document.querySelectorAll(`.${key} input`);
                                let elementMessage = element.lastElementChild;

                                elementInput.forEach((event, key) => {
                                    elementInput[key].classList.add('error');
                                });

                                elementMessage.textContent = responseFromServer[key].error_message;
                            }
                        }
                    }
                    console.log(responseFromServer);
                } else {
                    console.log('Что-то пошло не так');
                }
            })
        });
    }

    // работа с авторизацией
    if (formAuth) {
        formAuth.addEventListener('submit', (e) => {
            e.preventDefault();

            let dataForm = new FormData(formAuth);

            let fdObject = {};

            dataForm.forEach((value, key) => {
                fdObject[key] = value;
            });
            let json = JSON.stringify(fdObject);

            let request = new XMLHttpRequest();
            request.open('POST', 'backend.php');
            request.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            request.send(json);

            request.addEventListener('load', () => {
                if (request.status === 200) {
                    responseObj = JSON.parse(request.response);
                    if (responseObj.error.errorStatus) {
                        //если логин неправильный 
                        if (responseObj.error.loginError === 'Y') {
                            if (!inputLogin.classList.contains('error')) {
                                inputLogin.classList.add('error');
                                inputLogin.value = '';
                                inputLogin.placeholder = responseObj.error.loginErrorMessage;
                            }
                        } else {
                            if (inputLogin.classList.contains('error')) {
                                inputLogin.classList.remove('error');
                                inputLogin.placeholder = '';
                                inputLogin.value = fdObject.login;
                            }
                        }
                        //если пароль неправильный
                        if (responseObj.error.passwordError === 'Y') {
                            if (!inputPassword.classList.contains('error')) {
                                inputPassword.classList.add('error');
                                inputPassword.value = '';
                                inputPassword.placeholder = responseObj.error.passwordErrorMessage;
                            }
                        } else {
                            if (inputPassword.classList.contains('error')) {
                                inputPassword.classList.remove('error');
                                inputPassword.placeholder = '';
                                inputPassword.value = fdObject.login;
                            }
                        }

                    } else {
                        //если логин и пароль правильные.
                        window.location.href = "/personal/";
                    }
                } else {
                    console.log('что-то пошло не так');
                }
            })
        });
    }

    // редактирование данных пользователя
    let formUpdate = document.querySelector('.formUpdate');

    let formUpdateInputs = document.querySelectorAll('.formUpdate input');

    if (formUpdate) {

        formUpdateInputs.forEach((value, key) => {
            formUpdateInputs[key].addEventListener('input', () => {
                formUpdateInputs[key].parentElement.parentElement.lastElementChild.textContent = '';
            })
        });


        formUpdate.addEventListener('submit', (e) => {
            e.preventDefault();
            let dataForm = new FormData(formUpdate);
            let fdObject = {};

            dataForm.forEach((value, key) => {
                fdObject[key] = value;
            });
            console.log(fdObject);

            let jsonToServer = JSON.stringify(fdObject);

            let request = new XMLHttpRequest();
            request.open('POST', '../backend.php');
            request.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            request.send(jsonToServer);

            request.addEventListener('load', () => {
                if (request.status === 200) {
                    // console.log(request.response);
                    let responseFromServer = JSON.parse(request.response);
                    console.log(responseFromServer.error_status);
                    if (responseFromServer.error_status) {
                        for (let key in responseFromServer) {
                            if (responseFromServer[key].error_status) {
                                continue;
                            }
                            if (responseFromServer[key].error) {
                                let errorElem = document.querySelector(`.error-message__${key}`);
                                errorElem.textContent = responseFromServer[key].error_message;
                            }
                        }
                    } else {

                        showSuccessMessage(wrapper, "Изменеия успешно внесены!")

                        setInterval(() => {
                            window.location.href = '/personal/';
                        }, 1000)
                    }


                } else {
                    console.log('Что то пошло не так')
                }

            })

        });
    }
    //открытие и закрытие постов

    let postBox = document.querySelector('.posts-box');
    let postContent = document.querySelectorAll('.post__content');

    if (postBox) {
        postBox.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn__read-post')) {
                postContent.forEach((arItem) => {
                    arItem.removeAttribute('style');
                })
                let contentElem = e.target.parentElement.parentElement.nextElementSibling;
                contentElem.style.maxHeight = contentElem.scrollHeight + 'px';
            }
        });
    }
    // создание статьи
    let postAdd = document.querySelector('.post-add');
    if (postAdd) {
        postAdd.addEventListener('submit', (e) => {
            e.preventDefault();

            let titleErrorMessage = document.querySelector('input.title');

            titleErrorMessage.addEventListener('input', () => {
                titleErrorMessage.classList.remove('error');
                titleErrorMessage.nextElementSibling.textContent = '';
            });

            // элемент, содержащий файл выбранный пользователем
            var file = document.querySelector('.preview_img');

            let dataForm = new FormData(postAdd);

            let fdObject = {};
            dataForm.forEach((value, key) => {
                fdObject[key] = value;
            });
            console.log(fdObject);
            let request = new XMLHttpRequest();
            request.open('POST', '../../backend.php');
            request.send(dataForm);

            request.addEventListener('load', () => {
                if (request.status === 200) {
                    let jsonFormServer = JSON.parse(request.response);
                    if (jsonFormServer.title.error) {

                        titleErrorMessage.classList.add('error');
                        titleErrorMessage.nextElementSibling.textContent = jsonFormServer.title.error_message;
                    } else {
                        showSuccessMessage(wrapper, "Статья успешно сохранена");
                        let successfulMessage = document.querySelector('.successful-message');
                        setTimeout(() => {
                            successfulMessage.remove();
                            postAdd.reset();
                        }, 1500);
                    }

                } else {
                    console.log('Что-то пошло не так');
                }
            });

        });
    }

    // удаление статьи 
    let arrBtnRemovePosts = document.querySelectorAll('.post__btn-remove');
    if (arrBtnRemovePosts) {
        arrBtnRemovePosts.forEach((post) => {
            post.addEventListener('click', (e) => {
                let currentPostName = post.parentElement.parentElement.querySelector('.post-title').textContent;
                let decisien = confirm(`Вы точно хотите удалить статью \"${currentPostName}\"?`);
                
                if (decisien) {
                    let postElem = post.parentElement.parentElement.parentElement.parentElement;
                    let postId = postElem.getAttribute('id');

                    let formData = new FormData();

                    formData.append('mission', 'delete');
                    formData.append('post_id', postId);

                    let request = new XMLHttpRequest();
                    request.open('POST', '../../backend.php');
                    request.send(formData);

                    request.addEventListener('load', function () {
                        if (request.status === 200) {
                            if (request.response) {
                                postElem.remove();
                            }
                        }
                    });
                }


            });
        });
    }

    //переход на страницу редактирования cтатьи
    let arBtnEditBtn = document.querySelectorAll('.post__btn-edit');
    if (arBtnEditBtn) {
        arBtnEditBtn.forEach((post) => {
            post.addEventListener('click', (e) => {
                e.preventDefault();
                let postElem = post.parentElement.parentElement.parentElement.parentElement;
                let postId = postElem.getAttribute('id');

                let formData = new FormData();
                formData.append('current_post_id', postId);


                let request = new XMLHttpRequest();
                request.open('POST', '/backend.php');
                request.send(formData);
                request.addEventListener('load', () => {
                    if (request.status === 200) {
                        window.location.href = '/personal/myblog/edit.php';
                    } else {
                        console.log('Что-то пошло не так');
                    }
                });
            });
        });
    }

    //  редактирование статьи 
    let postEdit = document.querySelector('.post-edit');
    if (postEdit) {
        //отмена радактирования статьи
        let editCancel = document.querySelector('.btn__edit-cancel');
        editCancel.addEventListener('click', (e) => {
            e.preventDefault();

            let formData = new FormData();
            formData.append('current_post_id', '');
            let request = new XMLHttpRequest();
            request.open('POST', '/backend.php');
            request.send(formData);
            request.addEventListener('load', () => {
                if (request.status === 200) {
                    window.location.href = '/personal/myblog/';
                } else {
                    console.log('Что-то пошло не так');
                }
            });
        });

        // удаление изображения статьи со страницы и разблокировака инпута с типом файл.
        let removeImgBtn = document.querySelector('.img-box_remove');
        let inputImg = document.querySelector('.preview_img');
        if (removeImgBtn) {
            removeImgBtn.addEventListener('click', (e) => {
                e.preventDefault();
                inputImg.removeAttribute('disabled');
                removeImgBtn.parentElement.remove();
            });
        }

        postEdit.addEventListener('submit', (e) => {
            e.preventDefault();
            let formData = new FormData(postEdit);
            let fdObject = {};
            formData.forEach((value, key) => {
                 fdObject[key] = value;
            }); 
            console.log(fdObject);
            let request = new XMLHttpRequest();
            request.open('POST', '../../backend.php');
            request.send(formData);

            request.addEventListener('load', () => {
                if (request.status === 200) { 
                    if(request.response){
                        showSuccessMessage(wrapper, "Статья успешно изменена");
                        let successfulMessage = document.querySelector('.successful-message');
                        setTimeout(() => {
                             window.location.href = '/personal/myblog/';
                        }, 1500);
                    }
                }
            });
        });
    }

});