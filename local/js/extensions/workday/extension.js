BX.addCustomEvent("onTimeManWindowOpen",function(event){
        // console.log(event);
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