<?php

require('vendor/sourceNetwork/neuralNetwork.php');
require('vendor/sourceNetwork/matrix.php');
require('vendor/sourceNetwork/mathFunctions.php');

class Letter extends Network
{
    protected $network;
    protected $json;
    protected $data;

    public function __construct()
    {
        $this->json = '{"i_nodes":25,"h_nodes":30,"o_nodes":5,"learningRate":0.1,"bias_ih":[[-0.0014768206306042574],[1.3958055808609777],[-0.3982134378032916],[0.5741234837857968],[-1.2445423743217232],[0.7040067790288672],[0.14391078963844187],[-0.338601467874572],[0.9029097627784655],[0.06971924875381527],[0.7882093440439205],[-0.09746256054391605],[-1.8620707854174483],[-0.8090643276591669],[1.1269214450739715],[1.0322417394650785],[2.967785066114859],[0.2229708180353512],[-0.5896415956364146],[0.9190447109216953],[-0.5288217077736492],[1.3419964931816641],[-2.0772984378018116],[2.1101030207751803],[-1.7874060822344486],[0.6207299523222756],[-2.361625715686953],[-2.369609479752098],[1.8519371964311822],[-1.1264540768780096]],"bias_ho":[[0.8427713156768726],[-0.2010265437999517],[0.6837633952040593],[-0.43789443865524846],[0.4579932289441344]],"weights_ih":[[2.154212618782505,-0.1425573886899682,-0.12505259161936952,-1.5714828343425737,1.0512072020906786,-0.8177317123131506,-0.13163264452573617,-1.2794409971439866,2.342349298341193,3.1519515420708473,-0.35722516178270736,-0.20032693749932554,1.0245337119342592,0.0488944639598744,-1.7100333246762838,-1.3856199472930375,0.8839507066101675,2.189778605925349,0.45060813954633044,0.23814391845415928,0.817760836335048,-0.2123424739110708,-1.3364956100064522,1.1453882623918539,-2.021655682746801],[0.341857998542104,-1.1149000526558868,-0.1915681829181461,-1.468055271573867,-3.958570520484316,1.549697968849444,-0.14791496731839185,-2.1640035617202265,-1.6758129764750929,-1.8576342317589791,1.937259879883374,2.4844871367782777,1.3595981815693796,1.6823491887301876,-2.9560768626862313,2.6024168272908526,-1.357514438752186,-3.430872664271684,-1.5813128573187971,-1.0114466302952194,1.9740176810909693,1.5534706304971564,1.5504884503668246,-0.9401950948136498,-2.307930269852375],[-0.80342547737757,1.5774471388791036,1.1752462462495787,1.7873334766234747,-1.8001083656252486,0.1811380826579231,0.07496123228725103,0.9774153701831472,0.8740789430821182,0.6138143359876437,2.230484856413515,-0.8294327207717133,-1.8496610768492696,0.6671463800020396,-3.2383603938568646,4.447461103979561,-1.4202111395502957,-1.9403621269908584,0.8426521453988326,1.5984954942287755,2.5832957361668916,-2.0926033397913217,-2.4099508853607414,-3.2374420425061676,-0.3613142321192525],[-5.0981495140073605,-1.4939746888301717,-0.03264691583322909,3.443540256941865,2.942541328230388,-0.23563662631001006,2.7816695471569233,0.13941341684777453,-1.1323230684964625,-3.4781435511084555,0.12411193539018568,1.2331692691854113,-1.848280586897327,-1.1226173069157237,2.0038792212214056,-0.07284664840629876,-2.75204631456692,2.6122571251422655,-0.8306636581004212,3.2461325355144406,-4.75898557004957,2.321558053688191,2.7374777107107158,-2.048640270801848,4.249419143840445],[3.226038239239698,3.2992764941307606,0.4057199864632406,-1.6593939874495662,1.5013535611327855,-0.559039868578805,-3.0254816322194187,0.677885779849961,-1.620196528055425,1.5091935193453674,1.4751276779336797,-2.7554524619343534,0.5428931300691213,0.2337048321863327,2.052919303619581,-1.1113202927526302,-1.8131499882610536,-2.915203758217049,-1.2687903247573427,-5.596865793088837,-0.48497425398091043,2.608073921298313,-0.5488743124947587,2.8137086379422174,-2.0860680776582403],[-0.40020758260502237,-1.511984551743328,-1.7067289686815852,-0.62048709317784,0.6132242390955956,0.24492600653301452,-0.9926847624554517,-0.7370082793681737,-1.3494381739965613,0.08458269214036593,0.05529389072255667,-0.28079822901044227,-1.1607672507940747,-0.2699328755168651,1.6243959322678942,-0.40422855947020897,-0.14091500394083142,0.1624973526672029,1.0109430790283018,-1.462060192413964,-1.1982498215028954,-0.23576689502086975,-1.0657358188867976,0.2315486430213123,-1.3211492066396826],[-1.796542369596093,-1.5755523925491335,-0.5614850600916407,-0.44048568833877194,-1.0659121254834913,0.4516519712631886,0.5418029127564657,2.0383615334973033,-1.0293513386245607,-4.301210267177143,1.2184940921032845,-0.5637227322206677,0.381940951522049,-0.2874956050800506,5.233467213013868,1.0318667188259363,-1.8393991759871717,-0.35157065858506975,-1.6451880637618441,1.6141923319313685,-1.2220107316212732,0.16821403305005053,1.9398849194202235,-0.7511223430952491,1.3740410662048177],[0.15877603090080694,1.147885161182504,2.256627624026673,1.7721024183893996,1.5371531650241246,4.680665802690065,-0.13086651315711603,-2.302917749761732,-3.2810266345824832,-0.4750764936957089,3.4484048217294268,0.36963845473483087,-3.8208048364324028,3.436626503384243,-5.294421435679355,-1.1644943797124834,1.6475636146642159,2.315242686676475,1.691641262880923,0.6795299647356996,-2.391885219232123,-2.306247063862311,-3.5196812471545256,0.5155698362943102,-3.200761467519159],[0.13314923391179861,-1.8474275629639312,0.34964279328958425,-2.2236184324306816,-1.1237631754664337,1.1213533418808324,0.1917918780066829,-1.8875569139422967,0.9157955344422359,1.6578995400334984,1.8455878734875615,-0.14915963084902403,-1.406023682522799,1.3142216959467479,1.746819415544681,3.158761127831925,-1.6061136480849973,2.527946872006156,-0.8008946204442227,-0.8048040625081903,1.2003004263772068,0.07514247643540127,1.0976850343661955,-0.4980819002658894,-0.5168388671948215],[-1.059318608135417,-2.5248360105120464,-1.5441898339226745,-3.068047238419331,0.6412343091674705,-1.075506537187618,-0.6400394661668556,1.436092403566963,-0.6557315384694441,-1.3016681930843208,-0.6603993030484817,1.4509189907459636,2.954230025890882,0.47353311871250464,-0.23739749133329321,1.3292414717415222,-0.8283783474020209,1.0392390439619548,0.5575656823081296,0.9496198391582253,0.8053598673799848,-3.3940166420902247,-0.009360403854282766,-0.9236517755882442,-0.042463702894882424],[2.031465757380156,2.835886329270974,-1.2064301284562406,0.45082715613021845,-5.6526659523473635,-2.641966999695746,-0.9625343829509522,4.247556737288052,-0.20918282812374456,-0.07096074373470146,-1.3359658116689022,-3.6411176680103488,0.022130931711510315,-1.9671011706771966,2.3589314041310363,0.8836842850504094,-1.488642071494857,-1.5268864770830783,1.3619802079951056,-0.7008918509309524,1.5319294955297784,-0.3013858337358264,0.6691357227680302,1.7477846611062544,-1.4287219986320854],[-0.16257548871609773,2.1451799319089004,0.08074599770511806,0.22221465599874718,1.4053250212441781,0.8680575625980924,-0.5679179087660231,-0.32153092206784484,-0.021741099681426967,-0.14760959153176403,1.4336762337523032,1.5598328266369843,1.3643688872319992,2.4893755732765124,-0.6273269475652044,-1.4433196903084649,1.3278747033786735,-3.1430814931278976,1.3875160786401837,-4.1235616602287015,0.7317803284071042,0.13786462261808827,-2.4146990707020173,1.8539310346750382,0.7065960484285148],[-1.4373724919699828,-0.7728128171644029,-2.721179609064805,-0.5735686245998045,7.681446889484541,2.308872208905918,0.22782695747348686,-1.744074757690348,-1.2152431607251784,-2.307442917916022,0.6253044640488618,1.274982332359877,-2.4057515983966913,0.052278285527269694,-0.4749786297439046,-3.07420949008848,2.970799685687359,1.6878654527285242,-1.3650367083768185,-0.4656806558814881,-3.5289110630897906,2.5117191895011772,2.1602304350342,0.8428050625632534,-0.5429438685827],[-0.5097911371800208,-2.28241012885399,-1.7008509690410074,-2.269396885834763,-0.6147994344985245,-0.656070787609231,0.9274850119409032,-0.3622242293954649,1.3841684088169932,3.3323925055703043,-1.509809595968177,1.085108542858092,1.4881251897785928,1.6530135230238017,0.6024137894578527,1.5390531295185315,-1.6751221815987187,-4.138164757737858,-1.5698524384541537,-3.9085021802066717,2.3589452370259263,1.0513897603494522,-0.21552674771684532,-1.6366515881796404,3.4158015185613455],[1.3656370227600834,1.8342879732783464,2.435685073491292,2.116265851233271,-0.04265275553399457,0.9349338691103531,1.160877952376803,-1.601700957734349,0.5075816384380931,0.15181060343890287,-0.23777935747387974,-2.0126447707259008,-1.679561959548985,-0.22426035319390783,0.2343701350678403,-1.0365977274709994,0.15136156815959548,-0.8296969868681663,1.2585116557618135,-1.2259256571179933,-0.8287634293965302,3.203906265168875,1.2692871629766644,0.2942696606591243,-0.17226552799419165],[-1.9389139268310056,-2.967237615585423,0.060167576414182876,-2.923006633532099,1.1662759212763536,0.4250391563365035,0.3566448835274194,-1.4141812611067157,1.7011327479722196,-0.8879139097480263,-0.4429368794723001,1.2536033336058017,1.56153293832341,1.0763203984619356,-3.06916616765069,0.9922805347150884,0.008271894343660607,0.4851234357197143,-0.7261319773858583,0.9699608841485048,0.8511594321667642,2.0327338917232725,0.6274915900333943,2.1565996023148775,4.734835336728596],[0.6993271322467864,-1.566599194207048,-0.18813703927460168,-0.8132592025915043,-0.32973271879898103,-2.2051519640592225,0.8114755446911598,1.6491832367673132,2.5723682086592734,4.024282603190879,-2.42615046014786,-0.36306103362822184,1.0024050290353854,-0.18969201507534061,-3.8328855020458183,1.744185167141774,-2.0707275799338674,-1.4463466552368516,-0.6233582921121621,-3.7984069592986938,3.205939662888394,-0.7143218713036442,-0.2972434054531591,-3.738718581161685,2.4065601506348315],[1.25079114921305,-2.1735996897113017,-3.2855423453815438,-2.7738539630149863,-0.041890041283276745,-1.5994780605622887,0.35675614900128566,1.1144117818521424,1.2127126075396681,3.036766949179593,-1.029105026918431,-1.49771764119544,-0.5424114652630261,-2.9941038185410487,2.7172206848129266,-1.6647655829296741,0.8510063988951234,-2.6382437770556875,-0.07015610770541017,2.033684106359717,2.5146992417568614,1.184244843398889,-0.22312880777655206,1.4407870220448022,1.9317394990349919],[3.7406778672007337,1.0916788854503399,-3.314045180776397,-1.5683141547695547,-2.028628892940502,0.28413694850167953,-2.214132971759318,4.985224601454638,-3.899839099724329,-3.8352770256859503,0.5486301040128986,-3.2588327156531562,0.8003953711430045,1.5820751813364413,6.3595590887982665,-0.7128297256192614,-1.4119819627632104,-1.539546658405598,-0.372303016226939,-1.9661901833433801,0.11736477115079222,2.131567021605285,1.60176326958696,-0.11252703109530846,-3.8465996992299765],[-1.1388859744313429,-1.0011498699072443,-0.9627445468303154,1.1983668974545814,-3.3953303331030753,-1.6195157831696532,0.7404314213278329,2.2418647839292967,-0.8106329980892419,-4.003237966337585,-0.1906153062616271,0.7288797569364303,-0.5863465188947287,-1.4354103347721143,6.082343529128292,1.4597803440339783,-2.029464669407955,1.790964388242294,-1.2698602448694103,1.623537215818439,-2.422828750399519,1.3968921615904637,3.851645244451143,-1.6501425592233072,1.9999977473655095],[6.099943680062662,-0.11952609237720667,-3.653893887980804,-1.4959407077440054,0.5891519840871813,1.2069823408472724,0.7378911114216358,0.3491902837044274,-1.8740242990501086,1.677794951757241,-0.1390492015825686,0.20563843990156963,3.0358124566650853,3.5857305028628916,2.1494870004643953,-1.834810425943651,1.6488005671259178,-0.2689463583761849,-1.0184109224374482,2.2706251758187945,-0.8135028314740184,-0.07489438863592784,-1.0288745638077468,-1.122324096368949,-5.500014856515972],[-0.5010202771581628,-0.9955020132554737,-0.06899667179196887,-0.7863473091048901,-3.5640031469611135,-0.31445254869994677,0.2874995342488675,0.5755426594074325,-1.898007412543218,-5.113695219557568,2.372348780746886,-0.04973402482821431,0.06023272078132271,-1.4517309152232671,3.027243258455122,2.4027553282967182,-2.3220950287597533,-1.6134228067204328,-0.9619328977247052,1.952697968647845,1.0241143729158986,-0.1798870088011173,1.9439273284661893,-1.2042708912156397,1.8369699917603102],[-3.700981613229063,2.2768727394496624,2.9622887310493833,2.1348038190851435,-5.062195124028641,-0.006299190610013244,0.10840217262101041,-1.3658939096210727,-0.9037853876036931,0.8204916122811559,0.8482701966620436,1.94785772408352,-0.8797324635397642,-0.052485359265343555,-1.6793549819327331,4.023239829384378,0.3670424206116665,-0.06826960654786973,2.4765497024283287,-3.5381854199994134,-2.42582840536575,2.0225794120142373,3.473063849155817,-2.170529934023853,-0.007001818409003385],[0.47961359934906733,-2.2326792520397665,-2.0118054715281333,-2.6781170206026297,-2.7369395300403854,-0.7980780294155961,0.7690211408525688,0.9517659396614014,3.746341225720802,-0.7319273663000608,-0.2929776393924041,0.5248490667384333,3.4930057748137853,1.6105907411804852,-4.36175114793825,1.7785120124342897,1.5537263621701478,0.7391015291207138,1.847330498658371,-0.47641903103544875,3.0742876957468055,-0.9781510628200457,-1.440443119081748,1.339107600123817,-0.7352032081190212],[2.6303927634242728,0.8215201484861325,1.9052178323607112,-0.418546228140237,-0.5485019511644813,-0.15178804268037835,-0.6602594018504097,1.2255878076151177,-0.6403139469809395,0.6431239016038319,-1.3649768175662031,-0.24467875772970568,2.5202068044182284,1.1800405350296863,-0.5841467869270994,0.10130179175580137,0.20435113249405454,-1.725999192165525,0.36353122747334143,-1.1845609118087597,0.11567514036783957,-1.0382415347690912,-2.2806059364159275,-0.9878753387299491,0.8206528043756831],[0.7546708757064151,-3.0090317031126905,-1.2001537449628725,-1.18251127891875,-1.5248626664529135,-0.11094741193893504,1.1067235696228963,-0.1818715062795009,1.6047072719902982,-2.0706226216443495,0.158828039662976,0.17596661964372498,2.981807735344347,-0.6205910393439596,-1.8700385583675436,-0.15477362949732693,-0.4289408596050509,0.8066565827649704,-0.12829291323907088,-0.7995743642323938,0.7561135985532098,-1.140903749355016,-0.09320037637387428,0.3140539731678702,2.4367426509867913],[-0.05793334493382436,3.126906270422476,2.8190798011423506,0.13271936713059473,-0.2711531839341893,-0.2848617946971876,-1.4862600935714503,0.7952536107347553,-0.3987651344341671,-6.810416261534331,-0.22391770469275413,-0.1777894683910914,-0.44836658828181497,0.44390870107018526,-1.6083505355776229,1.6441265672123677,-2.010084703827669,1.1340562554928095,-0.22157945857918587,1.7561810016418078,0.8675387227760097,1.6246479344428164,-2.0576757237007173,5.30782445868634,-0.6625916625097562],[-2.594827906643957,1.3186543194195752,2.9023559597116515,2.446468383384023,1.3584127746530426,0.10609428723957438,-0.045711398616998404,-2.538450782807768,-3.131491174702154,2.6458245217989824,0.006083085465183176,0.7311616343707105,-3.707350635371153,-1.129962676155806,2.6105486289741324,-0.828996691893624,-1.6519718069517169,-0.49788946134512185,-2.49620315329511,1.0176232864693886,-2.5808929561970815,1.9695040877088024,0.011373847369648433,1.2304053660632888,1.3390658928081303],[-0.3561963677633996,-3.322075036951359,-0.3528797697622165,-2.3369528703510345,3.6785198224976297,-0.2325035285186046,0.6770865678172961,-1.4208514893036475,0.4659041285426797,6.698718005869854,-4.09842229311639,2.8197796301026075,1.6004658149770037,1.684114759325764,0.17776980047323207,-4.365685869491326,3.3443617684030555,1.4466508776186893,0.9928089546278723,-1.2510692478240828,-0.17542816635534297,-1.517146942638186,2.7745285521639507,-2.911965477955968,2.2046343559748727],[-3.250889262780007,2.1454655991558296,1.3140303023139175,2.9330573219632172,6.246206731425689,0.6780320175477483,-1.9633300148955077,0.38614620979871755,1.185034070755491,-4.511125170194124,-0.28488695619833165,-1.0759843392642863,-4.314537908767924,-0.9309159240244974,-1.1401404203164767,-0.12284730724588702,-0.8940030844014815,-1.7766939924098797,-1.249415665511325,3.294541585788339,0.25183236775476836,0.5593391770797355,-6.871372394151825,2.2453677326207626,1.2545914679711414]],"weights_ho":[[1.031381274982058,-1.4411418759406975,-2.0329448572691087,0.26893477918447667,1.408912741943932,-0.6007109662268169,-1.1107058530387193,0.8449779967472067,-1.3053153661106205,-1.6498429926912008,-0.8507322988567422,-0.2689137181029995,2.1194940213025366,0.2839840406948453,1.7993368506624525,-0.48700590030206214,0.021108382002968676,0.33378393472799145,-0.39823378747435256,-1.3015461114110767,1.8280011244071632,-2.3233124485966714,-2.0426914587556513,-1.0254862189330998,-0.4004299998968728,0.17562836190669479,-2.0071205570141775,0.5105972399038228,2.7660316451600915,-0.47480915559135034],[1.0430406516482336,-1.7981894963707206,0.7629380548631808,0.23838854745003604,-0.7627585007758466,-0.15645267843227503,0.09269688474119339,-2.5657271716957095,1.2104653539702355,1.4090340133259163,1.2682264595275754,0.182444447505405,-2.0839726753098717,-0.41996045089030104,-0.7337172407923914,0.8955532335585583,1.0584400407497805,2.8476990458834166,-1.434111641434036,-0.031430594654446586,-2.5331445420245835,-0.2605897628799786,-0.472923578947388,2.163745089319888,-0.853088347910799,1.651771649687303,-0.3466849614902025,-1.972132627865519,-0.40279826821886044,2.508993429328327],[-1.25434085985667,-1.1245355789850888,-1.5040271925349094,-0.4074918715461655,1.7671046871355784,0.3200649443009983,1.6249208573339102,-1.1436587042379296,-0.24126658520071945,-0.14084579374152292,1.1368511699930772,-0.42551757590617156,-0.36515662176045316,-0.5010672014922571,0.2759716596221657,-2.0255080880599396,-1.901844367521721,1.802697246215529,3.412557936932868,1.8411133213036204,1.5072461698030752,1.4797628255991466,-2.024548279053352,-0.7917171439885016,-0.30465206690489555,-0.6499930336886461,0.742729758370868,-0.29906949110104114,-2.13018159823449,-0.7586659463768269],[0.42212166130104695,-1.6727983541474263,0.05506837860696207,-1.0359298549445777,1.5461137080075662,0.6500762258227318,-0.8659541044298104,2.5785574345039266,-0.02723068347642025,-0.5073213975463203,-0.697744266596685,1.8536069119898428,0.653119307114151,-2.4419235535217494,-0.03906673480914396,1.5790203746472002,-2.4173160377346394,0.6296556771641959,-0.7416533024103453,-2.1971993080894654,-0.1466497721293523,-0.6324280743699398,-2.3307385076263287,1.280253669280585,-1.2726635486824296,-1.4657119639470289,2.2331111116647864,-0.6167798080072565,-1.8764668871370351,3.45505462335646],[-1.2968741191045305,-0.4443823821210582,-0.9763874244560619,3.0217830693795533,-2.1952533238989096,0.04336482583956398,1.4218773057738698,-0.746444054948022,0.9255858996308606,-0.7202029870867784,-2.348629583677297,-1.323200868446012,1.8755558789108595,-0.6156465912077835,0.6504150935937744,1.262981081856624,-0.5945602476909311,-1.4524518799892718,-1.7843616335388854,1.8162533285618871,-0.7633147640816503,0.8053661407413485,0.4225669202689103,-0.4733494204011476,-1.0340803567397487,-0.1354397557179975,-1.364099627094126,1.450252358932889,-0.5054368972576125,2.326439842516889]]}';
        $this->data = array(
            'inputs' => array(
                0 => "OXXXOXOOOXXXXXXXOOOXXOOOX", //A
                1 => "XXXXOXOOOXXXXXOXOOOXXXXXO", //B
                2 => "XXXXXXOOOOXOOOOXOOOOXXXXX", //C
                3 => "XXXXOXOOOXXOOOXXOOOXXXXXO", //D
                4 => "XXXXXXOOOOXXXXXXOOOOXXXXX", //E
                5 => "XXXXXXOOOOXXXXXXOOOOXOOOO", //F
                6 => "XXXXXXOOOOXOOXXXOOOXXXXXX", //G
                7 => "XOOOXXOOOXXXXXXXOOOXXOOOX", //H
                8 => "OOXOOOOOOOOOXOOOOXOOOOXOO", //I
                9 => "XXXXXOOOXOOOOXOXOOXOXXXXO", //J
                10 => "XOOXXXOXOOXXOOOXOXOOXOOXX", //K
                11 => "XOOOOXOOOOXOOOOXOOOOXXXXX", //L
                12 => "OXOXOXOXOXXOOOXXOOOXXOOOX", //M
                13 => "OXOOXXOXOXXOXOXXOXOXXOOXO", //N
                14 => "XXXXXXOOOXXOOOXXOOOXXXXXX", //O
                15 => "XXXXXXOOOXXXXXXXOOOOXOOOO", //P
                16 => "XXXXXXOOOXXOOOXXXXXXOOXOO", //Q
                17 => "XXXXXXOOOXXXXXXXOXOOXOOXX", //R
                18 => "OXXXXXOOOOOXXXOOOOOXXXXXO", //S
                19 => "XXXXXOOXOOOOXOOOOXOOOOXOO", //T
                20 => "XOOOXXOOOXXOOOXXOOOXXXXXX", //U
                21 => "XOOOXXOOOXXOOOXOXOXOOOXOO", //V
                22 => "XOOOXXOOOXXOOOXXOXOXOXOXO", //W
                23 => "XOOOXOXOXOOOXOOOXOXOXOOOX", //X
                24 => "XOOOXOXOXOOOXOOOOXOOOOXOO", //Y
                25 => "XXXXXOOOXOOOXOOOXOOOXXXXX", //Z
            ),
            'outputs' => array(
                0 => array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 1), //A
                1 => array(0 => 0, 1 => 0, 2 => 0, 3 => 1, 4 => 0), //B
                2 => array(0 => 0, 1 => 0, 2 => 0, 3 => 1, 4 => 1), //C
                3 => array(0 => 0, 1 => 0, 2 => 1, 3 => 0, 4 => 0), //D
                4 => array(0 => 0, 1 => 0, 2 => 1, 3 => 0, 4 => 1), //E
                5 => array(0 => 0, 1 => 0, 2 => 1, 3 => 1, 4 => 0), //F
                6 => array(0 => 0, 1 => 0, 2 => 1, 3 => 1, 4 => 1), //G
                7 => array(0 => 0, 1 => 1, 2 => 0, 3 => 0, 4 => 0), //H
                8 => array(0 => 0, 1 => 1, 2 => 0, 3 => 0, 4 => 1), //I
                9 => array(0 => 0, 1 => 1, 2 => 0, 3 => 1, 4 => 0), //J
                10 => array(0 => 0, 1 => 1, 2 => 0, 3 => 1, 4 => 1), //K
                11 => array(0 => 0, 1 => 1, 2 => 1, 3 => 0, 4 => 0), //L
                12 => array(0 => 0, 1 => 1, 2 => 1, 3 => 0, 4 => 1), //M
                13 => array(0 => 0, 1 => 1, 2 => 1, 3 => 1, 4 => 0), //N
                14 => array(0 => 0, 1 => 1, 2 => 1, 3 => 1, 4 => 1), //O
                15 => array(0 => 1, 1 => 0, 2 => 0, 3 => 0, 4 => 0), //P
                16 => array(0 => 1, 1 => 0, 2 => 0, 3 => 0, 4 => 1), //Q
                17 => array(0 => 1, 1 => 0, 2 => 0, 3 => 1, 4 => 0), //R
                18 => array(0 => 1, 1 => 0, 2 => 0, 3 => 1, 4 => 1), //S
                19 => array(0 => 1, 1 => 0, 2 => 1, 3 => 0, 4 => 0), //T
                20 => array(0 => 1, 1 => 0, 2 => 1, 3 => 0, 4 => 1), //U
                21 => array(0 => 1, 1 => 0, 2 => 1, 3 => 1, 4 => 0), //V
                22 => array(0 => 1, 1 => 0, 2 => 1, 3 => 1, 4 => 1), //W
                23 => array(0 => 1, 1 => 1, 2 => 0, 3 => 0, 4 => 0), //X
                24 => array(0 => 1, 1 => 1, 2 => 0, 3 => 0, 4 => 1), //Y
                25 => array(0 => 1, 1 => 1, 2 => 0, 3 => 1, 4 => 0), //Z
            ),
            'letters' => array(
                0 => 'A',
                1 => 'B',
                2 => 'C',
                3 => 'D',
                4 => 'E',
                5 => 'F',
                6 => 'G',
                7 => 'H',
                8 => 'I',
                9 => 'J',
                10 => 'K',
                11 => 'L',
                12 => 'M',
                13 => 'N',
                14 => 'O',
                15 => 'P',
                16 => 'Q',
                17 => 'R',
                18 => 'S',
                19 => 'T',
                20 => 'U',
                21 => 'V',
                22 => 'W',
                23 => 'X',
                24 => 'Y',
                25 => 'Z',
            )
        );
        $this->network = parent::JSONToNetwork($this->json);
    }

    public static function untrainnedLetter()
    {
        $dumbNetwork = new self();
        $dumbNetwork->network = new Network(25, 30, 5);
        $dumbNetwork->json = $dumbNetwork->network->toJSON();
        return $dumbNetwork;
    }

    public function predictLetter($input)
    {
        $array = $this->stringToArray($input);
        $predict = $this->network->predict($array);
        $binary = '';
        foreach ($predict as $key => $value) {
            $binary .= round($predict[$key][0]);
        }
        if((bindec($binary)-1) < 0 || (bindec($binary)-1) > 25) 
        {
            return 'Falhou';
        }
        return $this->data['letters'][bindec($binary) - 1];
    }

    public function trainLetter($effort)
    {
        for ($i = 0; $i < $effort; $i++) {
            for ($index = 0; $index < count($this->data['inputs']); $index++) {
                $this->network->train($this->stringToArray($this->data['inputs'][$index]), $this->data['outputs'][$index]);
            }
        }
        return $this->network->toJSON();
    }

    public function countErrors()
    {
        $errors = 0;
        for ($index = 0; $index < count($this->data['inputs']); $index++) {
            if ($this->data['letters'][$index] != $this->predictLetter($this->data['inputs'][$index])) {
                echo $this->data['letters'][$index] . ' = ' . $this->predictLetter($this->data['inputs'][$index]) . '<br>';
                $errors += 1;
            }
        }
        return $errors;
    }

    public function getNetwork()
    {
        return $this->network;
    }

    protected function stringToArray($string)
    {
        $array = array();
        for ($i = 0; $i < strlen($string); $i++) {
            if ($string[$i] == 'X') {
                $array[$i] = 1;
            } else {
                $array[$i] = 0;
            }
        }
        return $array;
    }
}