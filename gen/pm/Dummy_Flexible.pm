NSiteTypes  =  1


SiteType   =  LJ126
NSites   =  2


#S1
x   =  0
y   =  0
z   =  0
sigma   =  6
epsilon   =  6
mass   =  6

#S2
x   =  13
y   =  3
z   =  -3
sigma   =  3.44
epsilon   =  4
mass   =  15.999

NRotAxes   =   auto

NIdfTypes  =  3

IdfType  =  Bond
NIdfs  =  3

Bond  =  1 2
R0  =  6
ForConst  =  7

Bond  =  2 3
R0  =  1.355
ForConst  =  2

Bond  =  3 4
R0  =  9
ForConst  =  8

IdfType  =  Angle
NIdfs  =  2

Angle  =  1 2 3 k
Angle0  =  108.9
ForConst  =  70782.43

Angle  =  2 3 4 9
Angle0  =  113.486
ForConst  =  1324851.5

IdfType  =  Dihedral
NIdfs  =  3

Dihedral  =  6 7 8 9
nmax  =  5
ForConst0  =  -55.5
gamma00  =  0.0
ForConst1  =  -371.68
gamma01  =  0.0
ForConst2  =  1237.12
gamma02  =  0.0
ForConst3  =  810.89
gamma03  =  0.0
ForConst4  =  -21.44
gamma04  =  0.0
ForConst5  =  109.89
gamma05  =  0.0
ScaleLJ14  =  0.0625
ScaleEl14  =  0.5

Dihedral  =  5 6 7 8
nmax  =  5
ForConst0  =  -55.5
gamma00  =  0.0
ForConst1  =  -371.68
gamma01  =  0.0
ForConst2  =  1237.12
gamma02  =  0.0
ForConst3  =  810.89
gamma03  =  0.0
ForConst4  =  -21.44
gamma04  =  0.0
ForConst5  =  109.89
gamma05  =  0.0
ScaleLJ14  =  0.0625
ScaleEl14  =  0.5

Dihedral  =  4 5 6 7
nmax  =  5
ForConst0  =  -55.5
gamma00  =  0.0
ForConst1  =  -371.68
gamma01  =  0.0
ForConst2  =  1237.12
gamma02  =  0.0
ForConst3  =  810.89
gamma03  =  0.0
ForConst4  =  -21.44
gamma04  =  0.0
ForConst5  =  109.89
gamma05  =  0.0
ScaleLJ14  =  0.0625
ScaleEl14  =  0.5

NConstrU  =  2

ConstrU  =  1 10
Constraint  =  2
NRotAxes   =   auto

ConstrU  =  2 11
Constraint  =  2
NRotAxes   =   auto

NRotAxes   =   auto
