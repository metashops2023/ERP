const screen_first = document.querySelector('.calculator-bg__main__screen__first')
const screen_second = document.querySelector('.calculator-bg__main__screen__second')
const acBtn = document.querySelector('.calculator-bg__main__ac');
const delBtn = document.querySelector('.calculator-bg__main__del');
const resultBtn = document.querySelector('.calculator-bg__main__result');
const operatorBtns = document.querySelectorAll('.calculator-bg__main__operator');
const numBtns = document.querySelectorAll('.calculator-bg__main__num');



class Calculator{
    constructor(textElement_first, textElement_second){
        this.textElement_first = textElement_first;
        this.textElement_second = textElement_second;
        this.input_num_stream = [];
        this.input_operator_stream = [];
        this.current_num = '';
    }

    ommit(){
        this.current_num = '';
        this.input_num_stream.splice(0, this.input_num_stream.length);
        this.input_operator_stream.splice(0, this.input_operator_stream.length);
    }

    appendDigit(number){
        if(this.current_num === ''){
            if(number.innerText === '.'){
                this.current_num += '0';
                this.current_num += number.innerText;
            }else{
                this.current_num += number.innerText;
            }
            this.input_num_stream.push(this.current_num);
        }else{
            if(number.innerText === '.'){
                if(this.current_num.includes('.')){
                    return;
                }
            }
            this.current_num += number.innerText;
            this.input_num_stream[(this.input_num_stream.length) - 1] = this.current_num;
        }
        
    }

    chooseOperation(operator){
        if(this.input_num_stream.length === 0){
            this.input_num_stream.push(0);
        }
        if(this.input_operator_stream.length === this.input_num_stream.length) return;
        this.operator = operator.innerText;
        this.input_operator_stream.push(this.operator);
        this.current_num = '';
    }

    delete(){
        if(this.input_num_stream.length > this.input_operator_stream.length){
            const last_num = this.input_num_stream[this.input_num_stream.length - 1];
            const new_num = last_num.slice(0, last_num.length-1);
            this.input_num_stream.pop();
            if(new_num !== ''){
                this.input_num_stream.push(new_num);
                this.current_num = new_num;
            }else{
                this.current_num = '';
            }
            
        }else{
            this.input_operator_stream.pop();
        }
    }

    calculate(){
        let compute;
        
        
            let first_num, second_num, operator;
            let first_count = 1, second_count = 0;
            compute = parseFloat(this.input_num_stream[0]);
            while(first_count < this.input_num_stream.length){
                second_num = parseFloat(this.input_num_stream[first_count]);
                operator = this.input_operator_stream[second_count];
                if(isNaN(second_num)){
                    compute = compute;
                }else{
                    switch(operator){
                        case '+':
                            compute += second_num;
                            break;
                        case '-':
                            compute -= second_num;
                            break;
                        case 'x':
                            compute *= second_num;
                            break;
                        case '/':
                            compute /= second_num;
                    }
                    
                }
                first_count += 1;
                second_count += 1;
            }
        
        return compute;
    }
    updateScreen(){
        screen_first.classList.remove('small');
        screen_second.classList.remove('big');

        screen_first.classList.add('big');
        screen_second.classList.add('small');

        this.textElement_first.innerText = '';
        
            for(var i = 0; i < this.input_num_stream.length; i++){
                this.textElement_first.innerText += `${this.input_num_stream[i]}`
                if(this.input_operator_stream[i] !== undefined){
                    this.textElement_first.innerText += `${this.input_operator_stream[i]}`
                }
            }
        
        if(this.input_num_stream.length === 0){
            this.textElement_second.innerText = "0";
        }else{
            this.textElement_second.innerText = `= ${this.calculate()}`
        }
    }
}

const calc = new Calculator(screen_first, screen_second);
acBtn.addEventListener('click', ()=>{
    calc.ommit();
    calc.updateScreen()
})

numBtns.forEach(btn => {
    btn.addEventListener('click', ()=> {
        calc.appendDigit(btn);
        calc.updateScreen();
    })
})

operatorBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        calc.chooseOperation(btn);
        calc.updateScreen();
    })
})

delBtn.addEventListener('click', ()=>{
    calc.delete();
    calc.updateScreen();
})
resultBtn.addEventListener('click', ()=>{
    screen_first.classList.remove('big');
    screen_second.classList.remove('small');

    screen_first.classList.add('small');
    screen_second.classList.add('big');

})

