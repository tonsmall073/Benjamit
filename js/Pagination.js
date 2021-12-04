/*
---------------
coding By Tonsmall
date : 2021-12-03
---------------
ตั้งค่า setting
{
  "AllRow" : 1000,                                  จำนวนข้อมูลทั้งหมด
  "FocusCol" : 1,                                   อยู่ที่เลขหน้าอะไร
  "Length" : 1,                                     จำนวนข้อมูลที่แสดงต่อหน้า
  "ColNum" : 7,                                     จำนวนปุ่มทั้งหมดที่จะแสดง
  "FunctionActive" : 'functionName',                ฟังชั้นที่ต้องการให้ทำงานเมื่อมีการคลิกเปลียนหน้า              
  "FunctionSraty" : 'off'                           เมือเรียกใช้งาน function เริ่ม ทำงานทันที่ เมื่อ off เริ่มทำงานเมื่อมีการคลิกเปลียนหน้า
}
*/
async function asyncPagination(idRender,setting)
{
  try
  {
    const getSet = setting;

    const set = {
      "allRow" : parseInt(getSet.AllRow),
      "focusCol" : parseInt(getSet.FocusCol),
      "length" : parseInt(getSet.Length),
      "colNum" : parseInt(getSet.ColNum),
      "functionActive" : getSet.FunctionActive,               //ชื่อ function ที่ต้องการ เรียกใช้งาน เมื่อมีการคลิก (มี paramiter 1 ตัว)
      "functionStart" : getSet.FunctionStart,                 ////off = function จะไม่เริ่มทำงานทันที ที่มีการเรียกใช้งาน
      "start" : 0,
      "allCol" : 0
    };

    set.allCol = Math.ceil(getSet.AllRow / getSet.Length);
    set.start = (getSet.FocusCol - 1) * getSet.Length;

    const nav = document.createElement('NAV');

    const ul = document.createElement('UL');
    ul.classList.add('pagination');

    const liPrevious = document.createElement('LI');
    liPrevious.classList.add('page-item');
    liPrevious.innerHTML = `<a class="page-link" href="#" aria-label="Previous" onclick="asyncPaginationPrevious('${idRender}','${set.allRow}','${set.focusCol}','${set.length}','${set.colNum}','${set.functionActive}','${set.allCol}');">
                          <span aria-hidden="true">Previous</span>
                          <span class="sr-only">Previous</span>
                          </a>`;

    const liNext = document.createElement('LI');
    liNext.classList.add('page-item');
    liNext.innerHTML = `<a class="page-link" href="#" aria-label="Next" onclick="asyncPaginationNext('${idRender}','${set.allRow}','${set.focusCol}','${set.length}','${set.colNum}','${set.functionActive}','${set.allCol}');">
                        <span aria-hidden="true">Next</span>
                        <span class="sr-only">Next</span>
                        </a>`;

    const chkMin = (set.focusCol - Math.floor(set.colNum / 2));

    const chkMax = (set.focusCol + Math.floor(set.colNum / 2));

    if(set.focusCol == 1) liPrevious.classList.add('disabled');

    ul.appendChild(liPrevious); 
    
    if(set.allCol <= set.colNum)
    {
      for(let i = 1;i <= set.allCol;i++)
      {
        const liItem = document.createElement('Li');
        liItem.classList.add('page-item');
        if(getSet.FocusCol == i) liItem.classList.add('active');
        liItem.innerHTML = `<a class="page-link" href="#" 
        onclick="asyncPaginationFocus('${idRender}','${set.allRow}','${i}','${set.length}','${set.colNum}','${set.functionActive}','${set.allCol}');" >${i}</a>`;
        ul.appendChild(liItem);
      }
    }
    else if(chkMin <= 1)
    {
      for(let i = 1;i <= (set.colNum - 2);i++)
      {
        const liItem = document.createElement('Li');
        liItem.classList.add('page-item');
        if(getSet.FocusCol == i) liItem.classList.add('active');
        liItem.innerHTML = `<a class="page-link" href="#" 
        onclick="asyncPaginationFocus('${idRender}','${set.allRow}','${i}','${set.length}','${set.colNum}','${set.functionActive}','${set.allCol}');" >${i}</a>`;
        ul.appendChild(liItem);
      }

      const liItemDot = document.createElement('Li');
      liItemDot.classList.add('page-item');
      liItemDot.classList.add('disabled');
      liItemDot.innerHTML = '<a class="page-link" href="#">...</a>';
      ul.appendChild(liItemDot);

      const liItemMax = document.createElement('Li');
      liItemMax.classList.add('page-item');
      liItemMax.innerHTML = `<a class="page-link" href="#" 
      onclick="asyncPaginationFocus('${idRender}','${set.allRow}','${set.allCol}','${set.length}','${set.colNum}','${set.functionActive}','${set.allCol}');" >${set.allCol}</a>`;
      ul.appendChild(liItemMax);
    }
    else if(chkMax >= set.allCol)
    {
      const liItemMin = document.createElement('Li');
      liItemMin.classList.add('page-item');
      liItemMin.innerHTML = `<a class="page-link" href="#" 
      onclick="asyncPaginationFocus('${idRender}','${set.allRow}','1','${set.length}','${set.colNum}','${set.functionActive}','${set.allCol}');" >1</a>`;
      ul.appendChild(liItemMin);

      const liItemDot = document.createElement('Li');
      liItemDot.classList.add('page-item');
      liItemDot.classList.add('disabled');
      liItemDot.innerHTML = `<a class="page-link" href="#">...</a>`;
      ul.appendChild(liItemDot);

      let lowerMax = (set.allCol - set.colNum) + 3;

      for(let i = lowerMax;i <= set.allCol;i++)
      {
        const liItem = document.createElement('Li');
        liItem.classList.add('page-item');
        if(getSet.FocusCol == i) liItem.classList.add('active');
        liItem.innerHTML = `<a class="page-link" href="#" 
        onclick="asyncPaginationFocus('${idRender}','${set.allRow}','${i}','${set.length}','${set.colNum}','${set.functionActive}','${set.allCol}');" >${i}</a>`;
        ul.appendChild(liItem);
      }
    }
    else
    {
      const betweenNum = (set.colNum - 3) / 2;
      const betweenMin = (set.focusCol - Math.ceil(betweenNum) + 1);
      const betweenMax = (set.focusCol + Math.floor(betweenNum) - 1);
      
      const liItemMin = document.createElement('Li');
      liItemMin.classList.add('page-item');
      liItemMin.innerHTML = `<a class="page-link" href="#" 
      onclick="asyncPaginationFocus('${idRender}','${set.allRow}','1','${set.length}','${set.colNum}','${set.functionActive}','${set.allCol}');" >1</a>`;
      ul.appendChild(liItemMin);

      const liItemMinDot = document.createElement('Li');
      liItemMinDot.classList.add('page-item');
      liItemMinDot.classList.add('disabled');
      liItemMinDot.innerHTML = `<a class="page-link" href="#">...</a>`;
      ul.appendChild(liItemMinDot);

      for(let i = betweenMin; i <= betweenMax;i++)
      {
        const liItem = document.createElement('Li');
        liItem.classList.add('page-item');
        if(getSet.FocusCol == i) liItem.classList.add('active');
        liItem.innerHTML = `<a class="page-link" href="#" 
        onclick="asyncPaginationFocus('${idRender}','${set.allRow}','${i}','${set.length}','${set.colNum}','${set.functionActive}','${set.allCol}');" >${i}</a>`;
        ul.appendChild(liItem);
      }

      const liItemMaxDot = document.createElement('Li');
      liItemMaxDot.classList.add('page-item');
      liItemMaxDot.classList.add('disabled');
      liItemMaxDot.innerHTML = `<a class="page-link" href="#">...</a>`;
      ul.appendChild(liItemMaxDot);

      const liItemMax = document.createElement('Li');
      liItemMax.classList.add('page-item');
      liItemMax.innerHTML = `<a class="page-link" href="#" 
      onclick="asyncPaginationFocus('${idRender}','${set.allRow}','${set.allCol}','${set.length}','${set.colNum}','${set.functionActive}','${set.allCol}');" >${set.allCol}</a>`;
      ul.appendChild(liItemMax);
    }

    if(set.allCol == set.focusCol) liNext.classList.add('disabled');

    ul.appendChild(liNext); 

    nav.appendChild(ul);

    document.querySelector(idRender).innerHTML = '';

    document.querySelector(idRender).appendChild(nav);

    if(set.functionActive != '' && set.functionStart != 'off')
    {
      set.functionActive += `('${JSON.stringify(set)}')`;
      eval(set.functionActive);
    }

    return true;
  }
  catch(err)
  {
    alert(`Function paginationByTon Message Error : ${err.message}`);
    return false;
  }
}
async function asyncPaginationPrevious(idRender,allRow,focusCol,length,colNum,functionActive)
{
  try
  {
    const set = {
      "AllRow" : allRow,
      "FocusCol" : focusCol,
      "Length" : length,
      "ColNum" : colNum,
      "FunctionActive" : functionActive
    };
    console.log(set);
    set.FocusCol--;
    await asyncPagination(idRender,set);
    return true;
  }
  catch(err)
  {
    alert(`Function asyncPaginationPrevious Message Error : ${err.message}`);
    return false;
  }
}
async function asyncPaginationNext(idRender,allRow,focusCol,length,colNum,functionActive)
{
  try
  {
    const set = {
      "AllRow" : allRow,
      "FocusCol" : focusCol,
      "Length" : length,
      "ColNum" : colNum,
      "FunctionActive" : functionActive
    };
    set.FocusCol++;
    await asyncPagination(idRender,set);
    return true;
  }
  catch(err)
  {
    alert(`Function asyncPaginationNext Message Error : ${err.message}`);
    return false;
  }
}
async function asyncPaginationFocus(idRender,allRow,focusCol,length,colNum,functionActive)
{
  try
  {
    const set = {
      "AllRow" : allRow,
      "FocusCol" : focusCol,
      "Length" : length,
      "ColNum" : colNum,
      "FunctionActive" : functionActive
    };
    await asyncPagination(idRender,set);
    return true;
  }
  catch(err)
  {
    alert(`Function asyncPaginationFocus Message Error : ${err.message}`);
    return false;
  }
}