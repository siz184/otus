    BX.addCustomEvent("onTimemanInit",function(){

        changeTimemanButton();

    })

    BX.addCustomEvent("onTimeManDataRecieved",function(){

        changeTimemanButton();

    })
    
    function waitForElement(selector, callback) {
        const observer = new MutationObserver(() => {
            const el = document.querySelector(selector);
            if (el) {
            observer.disconnect(); // перестаём наблюдать
            callback(el);
            }
        });

        observer.observe(document.body, {
            childList: true,     
            subtree: true      
        });
    }
    
    function changeTimemanButton(){

        console.log(window.BXTIMEMAN);
        
                if(window.BXTIMEMAN.DATA.STATE=='PAUSED' || window.BXTIMEMAN.DATA.STATE=='CLOSED'){   

                    let contentText = 'Вы уверены, что хотите продолжить день?';
                    let titleText = 'Продолжение рабочего дня';
                    let buttonText = 'Продолжить день';

                    if(window.BXTIMEMAN.DATA.STATE=='CLOSED'){

                        contentText = 'Вы уверены, что хотите начать день?';
                        titleText = 'Начало рабочего дня';
                        buttonText = 'Начать день';

                    }

                    waitForElement('.tm-control-panel__actions-item', (el) => {
                    console.log('Элемент появился!', el);

                        const existButton = document.getElementById('myButton');

                        if(!existButton){
        
                        var workdayButtonA = document.querySelector('.tm-control-panel__actions-item');
                        workdayButtonA.style.display = 'none';
                        let newWorkdayButtonA = workdayButtonA.cloneNode(true);
                        newWorkdayButtonA.classList.remove('tm-control-panel__actions-item');  
                        newWorkdayButtonA.id = 'myButton';  
                        newWorkdayButtonA.style.display = 'block';
                        workdayButtonA.after(newWorkdayButtonA);

                        var workdayPopupA = new BX.PopupWindow("workdayContinueA",null,{
                                    content: contentText,
                                    titleBar: {content: BX.create("h2",{html:titleText})},
                                    closeIcon: {right: "20px", top: "10px"},
                                    width: 400,
                                    height: 190,
                                    zIndex: 100,
                                    buttons: [
                                        new BX.PopupWindowButton({
                                            text: buttonText,
                                            id: "workdayContinueButtonA",
                                            events: {
                                                click: function(){
                                                    this.popupWindow.close();
                                                    workdayContinueA.remove();
                                                    newWorkdayButtonA.remove();
                                                    workdayButtonA.querySelector('button').click();
                                                }
                                            }
                                        })
                                    ]
                                });

                        newWorkdayButtonA.onclick = function() { 
                            workdayPopupA.show();  
                        };  

                        }
                
                    })    

                }

            

    }
    

    
    BX.addCustomEvent("onTimeManWindowOpen",function(event){
        console.log(event);
        if(event.DATA.STATE=='PAUSED'){   
            var workdayButton = document.querySelector('.tm-popup-timeman-layout-time');
            workdayButton.style.display = 'none';
            let newWorkdayButton = workdayButton.cloneNode(true);
            newWorkdayButton.classList.remove('tm-popup-timeman-layout-time');  
            newWorkdayButton.style.display = 'block';
            workdayButton.after(newWorkdayButton);

            var workdayPopup = new BX.PopupWindow("workdayContinue",null,{
                        content: 'Вы уверены, что хотите продолжить день?',
                        titleBar: {content: BX.create("h2",{html:"Продолжение рабочего дня"})},
                        closeIcon: {right: "20px", top: "10px"},
                        width: 400,
                        height: 190,
                        zIndex: 100,
                        buttons: [
                            new BX.PopupWindowButton({
                                text: "Продолжить день",
                                id: "workdayContinueButton",
                                events: {
                                    click: function(){
                                        this.popupWindow.close();
                                        workdayContinue.remove();
                                        newWorkdayButton.remove();
                                        workdayButton.querySelector('button').click();
                                    }
                                }
                            })
                        ]
                    });

            newWorkdayButton.onclick = function() { 
                workdayPopup.show();  
            };  
        }
        if(event.DATA.STATE=='CLOSED'){   
            var workdayButton = document.querySelector('.tm-popup-timeman-layout-button');
            workdayButton.style.display = 'none';
            let newWorkdayButton = workdayButton.cloneNode(true);
            newWorkdayButton.classList.remove('tm-popup-timeman-layout-button');  
            newWorkdayButton.style.display = 'block';
            workdayButton.after(newWorkdayButton);

            var workdayPopup = new BX.PopupWindow("workdayContinue",null,{
                        content: 'Вы уверены, что хотите начать рабочий день?',
                        titleBar: {content: BX.create("h2",{html:"Начало рабочего дня"})},
                        closeIcon: {right: "20px", top: "10px"},
                        width: 400,
                        height: 190,
                        zIndex: 100,
                        buttons: [
                            new BX.PopupWindowButton({
                                text: "Начать день",
                                id: "workdayContinueButton",
                                events: {
                                    click: function(){
                                        this.popupWindow.close();
                                        workdayContinue.remove();
                                        newWorkdayButton.remove();
                                        workdayButton.querySelector('button').click();
                                        workdayButton.style.display = 'block';
                                    }
                                }
                            })
                        ]
                    });

            newWorkdayButton.onclick = function() { 
                workdayPopup.show();  
            };  
        }
    });

