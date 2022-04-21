import React, { useCallback, useRef } from 'react'
import { View, Text, StyleSheet, Image, Dimensions, ImageBackground } from 'react-native'
import { GestureHandlerRootView, TapGestureHandler } from 'react-native-gesture-handler';
import Animated, { useAnimatedStyle, useSharedValue, withDecay, withDelay, withSpring } from 'react-native-reanimated';


export default function DoubleTap() {

    const scale = useSharedValue(0);
    
    const doubleTapRef = useRef();

    const animatedStyle = useAnimatedStyle(() => {

        return {
            transform : [
                { scale : Math.max(scale.value,0)}
            ]
        }
    });

    const onDoubleTap = useCallback(() => {
        //console.log('Double Tap');
        scale.value = withSpring(1, undefined, (isFinished) => {

            if(isFinished){
                scale.value = withDelay(500, withSpring(0));
            }
        });
    });



    return (
        <View style={styles.container}>
            <GestureHandlerRootView>
                <TapGestureHandler
                    waitFor={doubleTapRef}
                    onActivated={() => {
                        //console.log('Single Tap');
                    }}
                >
                    <TapGestureHandler
                        maxDelayMs={250}
                        ref={doubleTapRef}
                        numberOfTaps={2}
                        onActivated={onDoubleTap}
                    >
                        <Animated.View>
                            <ImageBackground 
                                source={require('../assets/image.jpg')} 
                                style={styles.image}
                            >
                                <Animated.Image 
                                    source={require('../assets/heart.png')}
                                    resizeMode={'center'}
                                    style={[
                                        styles.image,
                                        {
                                            shadowOffset : {
                                                width:0,
                                                height:20
                                            },
                                            shadowOpacity:0.25,
                                            shadowRadius : 20
                                        },
                                        animatedStyle
                                    ]}
                                />
                            </ImageBackground> 
                        </Animated.View>
                    </TapGestureHandler>
                </TapGestureHandler>
            </GestureHandlerRootView>
        </View>
    )
}

const  {width : SIZE} = Dimensions.get('window');

const styles = StyleSheet.create({
    container : {
        flex : 1,
        alignItems : 'center',
        justifyContent : 'center'
    },
    image : {
        width : SIZE,
        height : SIZE
    } 
})