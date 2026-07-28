$(function() {
  $(".spin").on("click", () => {
    console.log("clicked!");
    
    $(".wheel img").css("filter", "blur(8px)");
    $(".lead").css("filter", "blur(8px)");
    $(".down").css("filter", "blur(8px)");

    const segments = 37;
    const degreesPerSegment = 360 / segments;

    const randomSegment = Math.floor(Math.random() * segments);

    const fullRotations = getRandomInt(3, 4);

    const spinDegrees = randomSegment * degreesPerSegment + fullRotations * 360;

    // 現在の回転角度を保持する変数（外で宣言してあれば、それに足す）
    // 今回は単純に回すだけの例として使います
    $(".wheel img").css("transform", `rotate(${spinDegrees}deg)`);

    // 10秒後にぼかしOFF（CSSのtransitionに合わせる）
    setTimeout(() => {
      $(".wheel img").css("filter", "blur(0)");
    }, 5000);

    setTimeout(() => {
      $(".lead").css("filter", "blur(0)");
    }, 5200);

    setTimeout(() => {
      $(".down").css("filter", "blur(0)");
    }, 5200);
  }); 

  // 乱数関数
  function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }
});

$(function() {
  let currentRotation = 0;

  // セグメント配列。好きな文字列や数字に変更OK
  const segments = [
    "0", "32", "15", "19", "4", "21", "2", "25", "17", "34", "6", "27", "13", "36", "11", "30", "8", "23", "10", "5", "24", "16", "33", "1", "20", "14", "31", "9", "22", "18", "29", "7", "28", "12", "35", "3", "26"
  ];
  const segmentCount = segments.length;
  const degreesPerSegment = 360 / segmentCount;

  $(".spin").on("click", () => {
    $(".wheel img").css("filter", "blur(8px)");

    const randomSegment = Math.floor(Math.random() * segmentCount);
    const fullRotations = getRandomInt(3, 4);

    const spinDegrees = randomSegment * degreesPerSegment + fullRotations * 360;

    currentRotation += spinDegrees;

    $(".wheel img").css({
      "transform": `rotate(${currentRotation}deg)`,
      "transition": "transform 5s ease-out"
    });

    setTimeout(() => {
      $(".wheel img").css("filter", "blur(0)");

      // 現在の角度を360度以内に正規化
      let normalizedDegree = currentRotation % 360;
      let pointerDegree = (360 - normalizedDegree + degreesPerSegment / 2) % 360;

      let segmentIndex = 0;
      for (let i = 0; i < segmentCount; i++) {
        const startAngle = i * degreesPerSegment;
        const endAngle = startAngle + degreesPerSegment;
        if (pointerDegree >= startAngle && pointerDegree < endAngle) {
          segmentIndex = i;
          break;
        }
      }

      let goon=segments[segmentIndex];

      $("#result").text("Now: " + goon);
      $("#goonVal").val(goon);
    }, 5800); // transition時間に合わせて
  });

  function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }
});





