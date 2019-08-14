
//function b64Sha1(s){return binb2B64(coreSha1(str2Binb(s),s.length * 8));}

///*
// * Calculate the SHA-1 of an array of big-endian words, and a bit length
// */
//function coreSha1(x, len)
//{
//  /* append padding */
//  x[len >> 5] |= 0x80 << (24 - len % 32);
//	x[((len + 64 >> 9) << 4) + 15] = len;

//  var w = Array(80);
//  var a =  1732584193;
//  var b = -271733879;
//  var c = -1732584194;
//  var d =  271733878;
//  var e = -1009589776;

//  for(var i = 0; i < x.length; i += 16)
//  {
//    var olda = a;
//    var oldb = b;
//    var oldc = c;
//    var oldd = d;
//    var olde = e;

//    for(var j = 0; j < 80; j++)
//    {
//      if(j < 16) w[j] = x[i + j];
//      else w[j] = rol(w[j-3] ^ w[j-8] ^ w[j-14] ^ w[j-16], 1);
//      var t = safeAdd(safeAdd(rol(a, 5), sha1Ft(j, b, c, d)),
//                       safeAdd(safeAdd(e, w[j]), sha1Kt(j)));
//      e = d;
//      d = c;
//      c = rol(b, 30);
//      b = a;
//      a = t;
//    }

//    a = safeAdd(a, olda);
//    b = safeAdd(b, oldb);
//    c = safeAdd(c, oldc);
//    d = safeAdd(d, oldd);
//    e = safeAdd(e, olde);
//  }
//  return [a, b, c, d, e];

//}

///*
// * Perform the appropriate triplet combination function for the current
// * iteration
// */
//function sha1Ft(t, b, c, d)
//{
//  if(t < 20) return (b & c) | ((~b) & d);
//  if(t < 40) return b ^ c ^ d;
//  if(t < 60) return (b & c) | (b & d) | (c & d);
//  return b ^ c ^ d;
//}

///*
// * Determine the appropriate additive constant for the current iteration
// */
//function sha1Kt(t)
//{
//  return (t < 20) ?  1518500249 : (t < 40) ?  1859775393 :
//         (t < 60) ? -1894007588 : -899497514;
//}

///*
// * Add integers, wrapping at 2^32. This uses 16-bit operations internally
// * to work around bugs in some JS interpreters.
// */
//function safeAdd(x, y)
//{
//  var lsw = (x & 0xFFFF) + (y & 0xFFFF);
//  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
//  return (msw << 16) | (lsw & 0xFFFF);
//}

///*
// * Bitwise rotate a 32-bit number to the left.
// */
//function rol(num, cnt)
//{
//  return (num << cnt) | (num >>> (32 - cnt));
//}

///*
// * Convert an 8-bit or 16-bit string to an array of big-endian words
// * In 8-bit function, characters >255 have their hi-byte silently ignored.
// */
//function str2Binb(str)
//{
//  var bin = Array();
//  var mask = (1 << 8) - 1;
//  for(var i = 0; i < str.length * 8; i += 8)
//    bin[i>>5] |= (str.charCodeAt(i / 8) & mask) << (32 - 8 - i%32);
//  return bin;
//}

///*
// * Convert an array of big-endian words to a base-64 string
// */
//function binb2B64(binarray)
//{
//  var tab = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
//  var str = "";
//  for(var i = 0; i < binarray.length * 4; i += 3)
//  {
//    var triplet = (((binarray[i   >> 2] >> 8 * (3 -  i   %4)) & 0xFF) << 16)
//                | (((binarray[i+1 >> 2] >> 8 * (3 - (i+1)%4)) & 0xFF) << 8 )
//                |  ((binarray[i+2 >> 2] >> 8 * (3 - (i+2)%4)) & 0xFF);
//    for(var j = 0; j < 4; j++)
//    {
//      if(i * 8 + j * 6 > binarray.length * 32) str += "=";
//      else str += tab.charAt((triplet >> 6*(3-j)) & 0x3F);
//    }
//  }
//  return str;
//}