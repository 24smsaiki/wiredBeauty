let btnWithoutWB = document.getElementById('btnWithoutWB');
let imgSteps1 = document.getElementById('imgSteps1');
let btnWithWB = document.getElementById('btnWithWB');
let imgSteps2 = document.getElementById('imgSteps2');

imgSteps2.style.display = 'none';

btnWithoutWB.onclick = function () {
    document.getElementById('parentBtnWithoutWB').className = "cta-primary-1";
    document.getElementById('parentBtnWithWB').className = "cta-primary-disabled";
    imgSteps1.style.display = 'inline';
    imgSteps2.style.display = 'none';
}

btnWithWB.onclick = function () {
    document.getElementById('parentBtnWithoutWB').className = "cta-primary-disabled";
    document.getElementById('parentBtnWithWB').className = "cta-primary-1";
    imgSteps1.style.display = 'none';
    imgSteps2.style.display = 'inline';
}