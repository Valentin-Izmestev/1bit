window.addEventListener('load', ()=>{
    let wrapper = document.querySelector('.wrapper');
let formAuth = document.querySelector('.form');
let inputLogin = document.querySelector('.login');
let inputPassword = document.querySelector('.password');

let responseObj = {};

formAuth.addEventListener('submit', (e) => {
    e.preventDefault();

    let dataForm = new FormData(formAuth);

    let fdObject = {};

    dataForm.forEach((value, key) => {
        fdObject[key] = value;
    });
    console.log(fdObject);
    let json = JSON.stringify(fdObject);

    let request = new XMLHttpRequest();
    request.open('POST', 'backend.php');
    request.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    request.send(json);

    request.addEventListener('load', () => {
        if (request.status === 200) {
            responseObj = JSON.parse(request.response);
            // console.log('получаемые данные ');
            // console.log(responseObj);
            //если логин или пароль неправильны
            console.log(responseObj.error.errorStatus);
            if (responseObj.error.errorStatus) {
                //если логин неправильный 
                if (responseObj.error.loginError === 'Y') {
                    if (!inputLogin.classList.contains('error')) {
                        inputLogin.classList.add('error');
                        inputLogin.value = '';
                        inputLogin.placeholder = responseObj.error.loginErrorMessage;
                    }
                }else{
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
                }else{
                    if (inputPassword.classList.contains('error')) {
                        inputPassword.classList.remove('error');
                        inputPassword.placeholder = '';
                        inputPassword.value = fdObject.login;
                    }
                }

            }else{ 
                //если логин и пароль правильные.
                let userBox = document.querySelector('#user-box').content;
                let hellowTemlate = userBox.querySelector('.hellow');
                let hellowbox = hellowTemlate.cloneNode(true);
                let h1 = hellowbox.querySelector('h1');
                h1.innerHTML = responseObj.name +' '+ responseObj.patronymic +' '+ responseObj.surname;
                wrapper.append(hellowbox);
                formAuth.remove();
            }



        } else {
            console.log('что-то пошло не так');
        }
    })
});
})