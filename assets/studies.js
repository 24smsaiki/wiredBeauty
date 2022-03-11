let btnWithoutWB = document.getElementById('btnWithoutWB');
let imgSteps1 = document.getElementById('imgSteps1');
let btnWithWB = document.getElementById('btnWithWB');
let imgSteps2 = document.getElementById('imgSteps2');

let miniImg1 = document.getElementById('mini_1');
let miniImg2 = document.getElementById('mini_2');
let miniImg3 = document.getElementById('mini_3');
let bigImg = document.getElementById('big_img');

imgSteps2.style.display = 'none';

btnWithoutWB.onclick = function () {
    btnWithoutWB.className = "cta-primary-a2";
    btnWithWB.className = "cta-primary-a2-disabled";
    imgSteps1.style.display = 'inline';
    imgSteps2.style.display = 'none';
}

btnWithWB.onclick = function () {
    btnWithoutWB.className = "cta-primary-a2-disabled";
    btnWithWB.className = "cta-primary-a2";
    imgSteps1.style.display = 'none';
    imgSteps2.style.display = 'inline';
}

miniImg1.onclick = function () {
    bigImg.src = "../../images/library/page_studies_studies_services/img_truc_rouge.png";
}

miniImg2.onclick = function () {
    bigImg.src = "../../images/library/page_studies_studies_services/Capture_phone_app_01.png";
}

miniImg3.onclick = function () {
    bigImg.src = "../../images/library/page_studies_studies_services/Capture_phone_app_02.png";
}
